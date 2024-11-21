<?php

namespace DzWork\Core\Middleware;

use DzWork\Core\Request;

abstract class Middleware
{
    abstract public function handle(Request $request);
}
