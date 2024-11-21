<?php

use DzWork\Core\Router;

/** @var Router $router */

$router->api('v1', function($router) {
    $router->get('/', 'Api\ExampleController@index');
    $router->post('/data', 'Api\ExampleController@store');
});
