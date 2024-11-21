<?php

namespace DzWork\Core\Console\Commands;

class MigrateCommand {
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

        foreach ($migrations as $migration) {
            require_once $migration;
            $className = 'App\\Database\\Migrations\\' . pathinfo($migration, PATHINFO_FILENAME);
            $migration = new $className();
            
            try {
                $migration->up();
                echo "Migrated: " . basename($migration) . "\n";
            } catch (\Exception $e) {
                echo "Error migrating " . basename($migration) . ": " . $e->getMessage() . "\n";
            }
        }
    }

    public function getDescription() {
        return "Run database migrations";
    }
}
