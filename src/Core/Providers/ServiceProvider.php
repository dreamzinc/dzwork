<?php

namespace DzWork\Core\Providers;

use DzWork\Core\App;

abstract class ServiceProvider
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        //
    }

    public function boot()
    {
        //
    }
}
