<?php

namespace DzWork\Core\Console\Commands;

class ServeCommand {
    public function execute($args) {
        $host = $args[0] ?? 'localhost';
        $port = $args[1] ?? '8000';
        
        echo "Starting development server on http://{$host}:{$port}\n";
        shell_exec("php -S {$host}:{$port} -t public/");
    }

    public function getDescription() {
        return "Start the development server";
    }
}
