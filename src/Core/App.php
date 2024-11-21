<?php

namespace DzWork\Core;

use DzWork\Core\Request;
use DzWork\Core\Container;
use DzWork\Core\Logger;
use DzWork\Core\View;
use App\Providers\RouteServiceProvider;

class App {
    protected static $instance = null;
    protected $container;
    protected $router;
    protected $config = [];
    protected $booted = false;
    protected $providers = [];
    protected $debug = true; // Enable debug mode for development

    private function __construct() {
        $this->container = Container::getInstance();
        $this->router = new Router();
        $this->registerBaseBindings();
        $this->registerProviders();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function registerBaseBindings() {
        $this->container->singleton('app', $this);
        $this->container->singleton('router', $this->router);
        $this->container->singleton('request', function() {
            return new Request();
        });
        $this->container->singleton('logger', function($container) {
            return new Logger();
        });
    }

    protected function registerProviders()
    {
        $this->providers = [
            RouteServiceProvider::class
        ];

        foreach ($this->providers as $provider) {
            $provider = new $provider($this);
            $provider->register();
        }
    }

    public function loadConfig($path) {
        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            $this->config[$key] = require $file;
        }
    }

    public function config($key, $default = null) {
        return $this->config[$key] ?? $default;
    }

    public function boot() {
        if ($this->booted) return;

        try {
            // Load configurations
            $this->loadConfig(APP_PATH . '/config');

            // Boot service providers
            foreach ($this->providers as $provider) {
                $provider = new $provider($this);
                $provider->boot();
            }

            // Set error handling
            $this->setupErrorHandling();

            $this->booted = true;
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    protected function setupErrorHandling() {
        set_error_handler(function ($severity, $message, $file, $line) {
            if (!(error_reporting() & $severity)) {
                return;
            }
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler([$this, 'handleException']);
    }

    public function handleException($e) {
        try {
            $logger = $this->container->make('logger');
            $logger->error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        } catch (\Exception $loggerException) {
            // If logger fails, output to error log
            error_log($e->getMessage() . "\n" . $e->getTraceAsString());
        }

        if (php_sapi_name() === 'cli') {
            echo $e->getMessage() . "\n";
            echo $e->getTraceAsString() . "\n";
            exit(1);
        }

        http_response_code(500);
        
        try {
            // Load the error view directly from core
            ob_start();
            $message = $e->getMessage();
            $file = $this->debug ? $e->getFile() : null;
            $line = $this->debug ? $e->getLine() : null;
            $trace = $this->debug ? $e->getTraceAsString() : null;
            
            include __DIR__ . '/Views/errors/error.php';
            echo ob_get_clean();
        } catch (\Exception $viewException) {
            // Fallback if view fails
            if ($this->debug) {
                echo "<div style='font-family: system-ui, -apple-system; max-width: 800px; margin: 2rem auto; padding: 2rem; background: #1a1c2c; color: #fff; border-radius: 0.5rem;'>";
                echo "<h1 style='color: #ff6b6b; margin-bottom: 1rem;'>Error</h1>";
                echo "<p style='margin-bottom: 1rem;'><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<p style='margin-bottom: 0.5rem;'><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
                echo "<p style='margin-bottom: 1rem;'><strong>Line:</strong> " . $e->getLine() . "</p>";
                echo "<h2 style='color: #ff6b6b; margin-top: 2rem; margin-bottom: 1rem;'>Stack Trace:</h2>";
                echo "<pre style='background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 0.25rem; overflow-x: auto; color: #e2e8f0;'>" 
                    . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                echo "</div>";
            } else {
                echo "<div style='text-align: center; padding: 2rem; background: #1a1c2c; color: #fff;'>";
                echo "<h1>An error occurred</h1>";
                echo "<p>Please try again later.</p>";
                echo "</div>";
            }
        }

        exit(1);
    }

    public function run() {
        try {
            $this->boot();
            
            $request = $this->container->make('request');
            $response = $this->router->dispatch($request);
            
            if (!($response instanceof Response)) {
                $response = new Response($response);
            }
            
            $response->send();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function getRouter() {
        return $this->router;
    }

    public function getContainer() {
        return $this->container;
    }
}
