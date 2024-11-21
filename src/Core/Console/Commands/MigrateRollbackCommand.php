<?php

namespace DzWork\Core\Console\Commands;

class MigrateRollbackCommand {
    public function execute($args) {
        $migrationsPath = APP_PATH . '/Database/Migrations';
        if (!is_dir($migrationsPath)) {
            echo "No migrations found.\n";
            return;
        }

        $migrations = glob($migrationsPath . '/*.php');
        if (empty($migrations)) {
            echo "No migrations found.\n";
            return;
        }

        // Reverse order for rollback
        $migrations = array_reverse($migrations);

        foreach ($migrations as $migration) {
            require_once $migration;
            $className = 'App\\Database\\Migrations\\' . pathinfo($migration, PATHINFO_FILENAME);
            $migration = new $className();
            
            try {
                $migration->down();
                echo "Rolled back: " . basename($migration) . "\n";
            } catch (\Exception $e) {
                echo "Error rolling back " . basename($migration) . ": " . $e->getMessage() . "\n";
            }
        }
    }

    public function getDescription() {
        return "Rollback database migrations";
    }
}
