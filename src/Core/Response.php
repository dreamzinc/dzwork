<?php

namespace DzWork\Core;

class Response {
    private $content;
    private $statusCode;
    private $headers;

    public function __construct($content = '', $statusCode = 200, array $headers = []) {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = array_merge([
            'Content-Type' => 'text/html; charset=UTF-8'
        ], $headers);
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setStatusCode($code) {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }

    public function json($data) {
        $this->headers['Content-Type'] = 'application/json';
        $this->content = json_encode($data);
        return $this;
    }

    public function redirect($url) {
        $this->headers['Location'] = $url;
        $this->statusCode = 302;
        return $this;
    }

    public function send() {
        http_response_code($this->statusCode);
        
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
        return $this;
    }
}
