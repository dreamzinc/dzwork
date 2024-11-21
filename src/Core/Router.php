<?php

namespace DzWork\Core;

class Router {
    protected $routes = [];
    protected $namespace = '';
    protected $groupStack = [];
    protected $currentGroupMiddleware = [];

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    protected function addRoute($method, $uri, $handler)
    {
        $uri = $this->prependGroupPrefix($uri);
        
        if (is_string($handler) && $this->namespace) {
            $handler = $this->namespace . '\\' . ltrim($handler, '\\');
        }

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $this->currentGroupMiddleware
        ];
    }

    protected function prependGroupPrefix($uri)
    {
        if (!empty($this->groupStack)) {
            $prefix = '';
            foreach ($this->groupStack as $group) {
                if (isset($group['prefix'])) {
                    $prefix .= '/' . trim($group['prefix'], '/');
                }
            }
            return trim($prefix . '/' . trim($uri, '/'), '/');
        }
        return trim($uri, '/');
    }

    public function group(array $attributes, callable $callback)
    {
        $this->groupStack[] = $attributes;

        // Save current middleware state
        $previousMiddleware = $this->currentGroupMiddleware;

        // Add new middleware
        if (isset($attributes['middleware'])) {
            $this->currentGroupMiddleware = array_merge(
                $this->currentGroupMiddleware,
                (array) $attributes['middleware']
            );
        }

        call_user_func($callback, $this);

        array_pop($this->groupStack);

        // Restore previous middleware state
        $this->currentGroupMiddleware = $previousMiddleware;
    }

    public function get($uri, $handler)
    {
        $this->addRoute('GET', $uri, $handler);
    }

    public function post($uri, $handler)
    {
        $this->addRoute('POST', $uri, $handler);
    }

    public function put($uri, $handler)
    {
        $this->addRoute('PUT', $uri, $handler);
    }

    public function delete($uri, $handler)
    {
        $this->addRoute('DELETE', $uri, $handler);
    }

    public function api($version, callable $callback)
    {
        $this->group(['prefix' => "api/v{$version}"], $callback);
    }

    public function dispatch(Request $request)
    {
        try {
            $method = $request->getMethod();
            $uri = trim($request->getUri(), '/');

            foreach ($this->routes as $route) {
                if ($route['method'] === $method && $route['uri'] === $uri) {
                    return $this->runRoute($route, $request);
                }
            }

            throw new \Exception("Route not found: {$uri}");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function runRoute($route, Request $request)
    {
        try {
            // Run middleware stack
            if (!empty($route['middleware'])) {
                foreach ($route['middleware'] as $middleware) {
                    $middlewareClass = "App\\Middleware\\{$middleware}";
                    
                    if (!class_exists($middlewareClass)) {
                        throw new \Exception("Middleware not found: {$middlewareClass}");
                    }
                    
                    $middleware = new $middlewareClass();
                    $response = $middleware->handle($request);
                    
                    if ($response !== null) {
                        return $response;
                    }
                }
            }

            return $this->handleRoute($route['handler'], $request);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function handleRoute($handler, Request $request)
    {
        try {
            if (is_callable($handler)) {
                return call_user_func($handler, $request);
            }

            if (is_string($handler)) {
                [$controller, $method] = explode('@', $handler);
                
                if (!class_exists($controller)) {
                    throw new \Exception("Controller not found: {$controller}");
                }

                $instance = new $controller();
                
                if (!method_exists($instance, $method)) {
                    throw new \Exception("Method not found: {$method} in controller {$controller}");
                }

                return $instance->$method($request);
            }

            throw new \Exception('Invalid route handler');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
