<?php

use DzWork\Core\Router;

/** @var Router $router */

// Basic pages
$router->group(['middleware' => ['WebMiddleware']], function($router) {
    $router->get('/', 'HomeController@index');
    $router->get('/about', 'HomeController@about');
    $router->get('/contact', 'HomeController@contact');
});

// Dashboard routes
$router->group(['middleware' => ['WebMiddleware', 'AuthMiddleware']], function($router) {
    $router->get('/dashboard', 'DashboardController@index');
    
    // User management
    $router->group(['prefix' => 'users'], function($router) {
        $router->get('/', 'UsersController@index');
        $router->get('/create', 'UsersController@create');
        $router->post('/', 'UsersController@store');
        $router->get('/{id}', 'UsersController@show');
        $router->get('/{id}/edit', 'UsersController@edit');
        $router->put('/{id}', 'UsersController@update');
        $router->delete('/{id}', 'UsersController@destroy');
    });
});
