<?php

namespace App\Providers;

use DzWork\Core\Providers\RouteServiceProvider as BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected $namespace = 'App\Controllers';

    protected function loadRoutes()
    {
        // Load web routes
        $this->web(APP_PATH . '/routes/web.php');

        // Load API routes
        $this->api(APP_PATH . '/routes/api.php');
    }
}
