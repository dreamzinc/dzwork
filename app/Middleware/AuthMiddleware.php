<?php

namespace App\Middleware;

use DzWork\Core\Request;
use DzWork\Core\Middleware\Middleware;
use DzWork\Core\Response;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request)
    {
        // Add authentication logic here
        // For now, just allow all requests
        return null;

        // Example of authentication check:
        // if (!isset($_SESSION['user_id'])) {
        //     return new Response('Unauthorized', 401);
        // }
        // return null;
    }
}
