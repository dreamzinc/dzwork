<?php

namespace DzWork\Core;

class Logger {
    private static $logFile = 'storage/logs/app.log';
    private static $instance = null;

    public function __construct() {
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function error($message, $context = []) {
        $this->log('ERROR', $message, $context);
    }

    public function info($message, $context = []) {
        $this->log('INFO', $message, $context);
    }

    public function debug($message, $context = []) {
        $this->log('DEBUG', $message, $context);
    }

    private function log($level, $message, $context = []) {
        $date = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        $logMessage = "[$date] [$level] $message $contextStr\n";
        
        file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }
}
