<?php

namespace DzWork\Core;

class View {
    protected static $layout = 'layouts/app';
    protected $view;
    protected $data = [];
    protected $sections = [];
    protected $currentSection = null;

    public function __construct($view, $data = []) {
        $this->view = $view;
        $this->data = $data;
    }

    public static function make($view, $data = []) {
        return new static($view, $data);
    }

    public function with($key, $value = null) {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function extend($layout) {
        static::$layout = $layout;
        return $this;
    }

    public function section($name) {
        $this->currentSection = $name;
        ob_start();
        return $this;
    }

    public function endSection() {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
        return $this;
    }

    public function renderSection($name) {
        return $this->sections[$name] ?? '';
    }

    public function render() {
        extract($this->data);
        
        ob_start();
        include $this->getViewPath();
        $content = ob_get_clean();
        
        if (static::$layout) {
            ob_start();
            include $this->getLayoutPath();
            return ob_get_clean();
        }
        
        return $content;
    }

    protected function getViewPath() {
        $view = str_replace('.', '/', $this->view);
        $path = APP_PATH . "/Views/{$view}.php";
        
        if (!file_exists($path)) {
            throw new \Exception("View {$this->view} not found");
        }
        
        return $path;
    }

    protected function getLayoutPath() {
        $path = APP_PATH . "/Views/" . static::$layout . ".php";
        
        if (!file_exists($path)) {
            throw new \Exception("Layout " . static::$layout . " not found");
        }
        
        return $path;
    }

    public function __toString() {
        return $this->render();
    }
}
