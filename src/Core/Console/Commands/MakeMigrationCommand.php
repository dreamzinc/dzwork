<?php

namespace DzWork\Core\Console\Commands;

class MakeMigrationCommand {
    public function execute($args) {
        if (empty($args)) {
            echo "Migration name required\n";
            return;
        }

        $name = $args[0];
        $this->createMigration($name);
    }

    protected function createMigration($name) {
        $timestamp = date('Y_m_d_His');
        $className = str_replace([' ', '-', '_'], '', ucwords($name, ' -_'));
        
        $template = <<<PHP
<?php

namespace App\Database\Migrations;

use DzWork\Core\Migration;

class {$className} extends Migration {
    public function up() {
        \$this->schema->create('table_name', function(\$table) {
            \$table->id();
            // Add columns here
            \$table->timestamps();
        });
    }

    public function down() {
        \$this->schema->drop('table_name');
    }
}
PHP;

        $path = APP_PATH . "/Database/Migrations/{$timestamp}_{$name}.php";
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        
        file_put_contents($path, $template);
        echo "Migration created: {$path}\n";
    }

    public function getDescription() {
        return "Create a new migration";
    }
}
