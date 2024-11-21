<?php

namespace App\Middleware;

use DzWork\Core\Request;
use DzWork\Core\Middleware\Middleware;

class WebMiddleware extends Middleware
{
    public function handle(Request $request)
    {
        // Add web middleware logic here
        // For example: session handling, CSRF protection, etc.
        return null;
    }
}
