<?php

namespace DzWork\Core;

abstract class Controller
{
    protected $container;

    public function __construct()
    {
        $this->container = App::getInstance()->getContainer();
    }

    protected function view($template, $data = [])
    {
        $viewPath = APP_PATH . '/Views/' . $template . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View template not found: $template");
        }

        extract($data);
        
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}
