<?php

namespace DzWork\Core;

abstract class Migration {
    protected $connection;
    protected $schema;

    public function __construct() {
        $config = require APP_PATH . '/config/database.php';
        $this->connection = new \PDO(
            "mysql:host={$config['host']};dbname={$config['database']};charset=utf8",
            $config['username'],
            $config['password'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
        $this->schema = new Schema($this->connection);
    }

    abstract public function up();
    abstract public function down();
}
