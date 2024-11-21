<?php

namespace DzWork\Core;

abstract class Model {
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected static $connection;
    
    public function __construct() {
        if (!self::$connection) {
            $config = require APP_PATH . '/config/database.php';
            self::$connection = new \PDO(
                "mysql:host={$config['host']};dbname={$config['database']};charset=utf8",
                $config['username'],
                $config['password'],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        }
    }

    public static function all() {
        $model = new static();
        $stmt = self::$connection->query("SELECT * FROM {$model->getTable()}");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $model = new static();
        $stmt = self::$connection->prepare("SELECT * FROM {$model->getTable()} WHERE {$model->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function where($column, $operator, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $model = new static();
        $stmt = self::$connection->prepare("SELECT * FROM {$model->getTable()} WHERE {$column} {$operator} ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        $columns = implode(', ', array_keys($fields));
        $values = implode(', ', array_fill(0, count($fields), '?'));
        
        $stmt = self::$connection->prepare("INSERT INTO {$this->getTable()} ({$columns}) VALUES ({$values})");
        $stmt->execute(array_values($fields));
        
        return self::find(self::$connection->lastInsertId());
    }

    public function update($id, array $data) {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        $set = implode(', ', array_map(fn($field) => "{$field} = ?", array_keys($fields)));
        
        $stmt = self::$connection->prepare("UPDATE {$this->getTable()} SET {$set} WHERE {$this->primaryKey} = ?");
        $stmt->execute([...array_values($fields), $id]);
        
        return self::find($id);
    }

    public function delete($id) {
        $stmt = self::$connection->prepare("DELETE FROM {$this->getTable()} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    protected function getTable() {
        if ($this->table) {
            return $this->table;
        }
        
        $className = (new \ReflectionClass($this))->getShortName();
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';
    }
}
