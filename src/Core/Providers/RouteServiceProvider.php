<?php

namespace DzWork\Core\Providers;

use DzWork\Core\Router;

abstract class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = '';
    protected $router;

    public function boot()
    {
        $this->router = $this->app->getRouter();

        if ($this->namespace) {
            $this->router->setNamespace($this->namespace);
        }

        $this->loadRoutes();
    }

    abstract protected function loadRoutes();

    protected function web(string $path)
    {
        return $this->loadRoutesFrom($path);
    }

    protected function api(string $path)
    {
        return $this->loadRoutesFrom($path, 'api');
    }

    protected function loadRoutesFrom($path, $type = 'web')
    {
        $router = $this->router;
        
        if ($type === 'api') {
            $router->group(['prefix' => 'api'], function($router) use ($path) {
                require $path;
            });
        } else {
            require $path;
        }
    }
}
