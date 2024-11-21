<?php

namespace DzWork\Core;

class Request {
    protected $params = [];
    protected $method;
    protected $uri;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    }

    public function getMethod() {
        return $this->method;
    }

    public function getUri() {
        return $this->uri;
    }

    public function setParams(array $params) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    public function input($key = null, $default = null) {
        $input = array_merge($_GET, $_POST);
        
        if ($key === null) {
            return $input;
        }
        
        return $input[$key] ?? $default;
    }

    public function isGet() {
        return $this->method === 'GET';
    }

    public function isPost() {
        return $this->method === 'POST';
    }

    public function isPut() {
        return $this->method === 'PUT';
    }

    public function isDelete() {
        return $this->method === 'DELETE';
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
