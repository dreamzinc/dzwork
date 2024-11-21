<?php

namespace DzWork\Core;

class Container {
    private $bindings = [];
    private $instances = [];
    private static $instance = null;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind($abstract, $concrete = null) {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton($abstract, $concrete = null) {
        $this->bind($abstract, $concrete);
        if (!isset($this->instances[$abstract])) {
            $this->instances[$abstract] = $this->resolve($abstract);
        }
        return $this->instances[$abstract];
    }

    public function make($abstract) {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        return $this->resolve($abstract);
    }

    private function resolve($abstract) {
        $concrete = $this->bindings[$abstract] ?? $abstract;

        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }

        if (is_string($concrete)) {
            return $this->build($concrete);
        }

        return $concrete;
    }

    private function build($concrete) {
        $reflector = new \ReflectionClass($concrete);
        
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$concrete} is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {
            if ($parameter->getClass()) {
                $dependencies[] = $this->make($parameter->getClass()->getName());
            } else if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new \Exception("Cannot resolve dependency {$parameter->getName()}");
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}
