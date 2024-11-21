<?php

namespace DzWork\Core;

class Schema {
    private $connection;
    private $table;
    private $columns = [];
    private $foreignKeys = [];
    private $primaryKey = 'id';

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function create($table, $callback) {
        $this->table = $table;
        $callback($this);
        
        $sql = $this->generateCreateTableSQL();
        $this->connection->exec($sql);
    }

    public function drop($table) {
        $sql = "DROP TABLE IF EXISTS {$table}";
        $this->connection->exec($sql);
    }

    public function id() {
        $this->columns[] = "`{$this->primaryKey}` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function string($column, $length = 255) {
        $this->columns[] = "`{$column}` VARCHAR({$length})";
        return $this;
    }

    public function integer($column) {
        $this->columns[] = "`{$column}` INT";
        return $this;
    }

    public function text($column) {
        $this->columns[] = "`{$column}` TEXT";
        return $this;
    }

    public function timestamp($column) {
        $this->columns[] = "`{$column}` TIMESTAMP";
        return $this;
    }

    public function timestamps() {
        $this->columns[] = "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }

    public function foreignKey($column) {
        $this->columns[] = "`{$column}` BIGINT UNSIGNED";
        return $this;
    }

    public function references($table) {
        $column = end($this->columns);
        preg_match('/`(\w+)`/', $column, $matches);
        $columnName = $matches[1];
        
        $this->foreignKeys[] = "FOREIGN KEY (`{$columnName}`) REFERENCES {$table}(id)";
        return $this;
    }

    private function generateCreateTableSQL() {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (\n";
        $sql .= implode(",\n", $this->columns);
        
        if (!empty($this->foreignKeys)) {
            $sql .= ",\n" . implode(",\n", $this->foreignKeys);
        }
        
        $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        return $sql;
    }
}
