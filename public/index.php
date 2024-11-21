<?php

define('APP_PATH', dirname(__DIR__) . '/app');

require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = DzWork\Core\App::getInstance();
$app->run();
