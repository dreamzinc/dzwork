<?php

namespace App\Controllers;

use DzWork\Core\Controller;
use DzWork\Core\View;

class HomeController extends Controller
{
    public function index()
    {
        return View::make('home', [
            'title' => 'Welcome to DzWork'
        ]);
    }
}
