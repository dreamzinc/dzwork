<?php

namespace DzWork\Core\Console\Commands;

class MakeModelCommand {
    public function execute($args) {
        if (empty($args)) {
            echo "Model name required\n";
            return;
        }

        $name = $args[0];
        $options = array_slice($args, 1);
        
        // Create model
        $this->createModel($name);

        // Check for -mcr flag
        if (in_array('-mcr', $options)) {
            $this->createMigration($name);
            $this->createController($name);
            $this->createViews($name);
        }
    }

    protected function createModel($name) {
        $template = <<<PHP
<?php

namespace App\Models;

use DzWork\Core\Model;

class {$name} extends Model {
    protected \$fillable = [
        // Define fillable fields
    ];
}
PHP;

        $path = APP_PATH . "/Models/{$name}.php";
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        
        file_put_contents($path, $template);
        echo "Model created: {$path}\n";
    }

    protected function createMigration($name) {
        $timestamp = date('Y_m_d_His');
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)) . 's';
        
        $template = <<<PHP
<?php

namespace App\Database\Migrations;

use DzWork\Core\Migration;

class Create{$name}sTable extends Migration {
    public function up() {
        \$this->schema->create('{$tableName}', function(\$table) {
            \$table->id();
            \$table->timestamps();
        });
    }

    public function down() {
        \$this->schema->drop('{$tableName}');
    }
}
PHP;

        $path = APP_PATH . "/Database/Migrations/{$timestamp}_create_{$tableName}_table.php";
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        
        file_put_contents($path, $template);
        echo "Migration created: {$path}\n";
    }

    protected function createController($name) {
        $template = <<<PHP
<?php

namespace App\Controllers;

use App\Models\\{$name};
use DzWork\Core\Controller;
use DzWork\Core\Response;

class {$name}Controller extends Controller {
    public function index() {
        \$items = {$name}::all();
        return Response::view('{$name}.index', ['items' => \$items]);
    }

    public function show(\$id) {
        \$item = {$name}::find(\$id);
        return Response::view('{$name}.show', ['item' => \$item]);
    }

    public function create() {
        return Response::view('{$name}.create');
    }

    public function store(\$request) {
        \$data = \$request->input();
        \$item = (new {$name}())->create(\$data);
        return Response::json(['message' => '{$name} created', 'data' => \$item]);
    }

    public function edit(\$id) {
        \$item = {$name}::find(\$id);
        return Response::view('{$name}.edit', ['item' => \$item]);
    }

    public function update(\$request, \$id) {
        \$data = \$request->input();
        \$item = (new {$name}())->update(\$id, \$data);
        return Response::json(['message' => '{$name} updated', 'data' => \$item]);
    }

    public function destroy(\$id) {
        (new {$name}())->delete(\$id);
        return Response::json(['message' => '{$name} deleted']);
    }
}
PHP;

        $path = APP_PATH . "/Controllers/{$name}Controller.php";
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        
        file_put_contents($path, $template);
        echo "Controller created: {$path}\n";
    }

    protected function createViews($name) {
        $viewsPath = APP_PATH . "/Views/" . strtolower($name);
        if (!is_dir($viewsPath)) {
            mkdir($viewsPath, 0777, true);
        }

        // Create index view
        $indexTemplate = <<<HTML
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{$name} List</h1>
        <a href="/<?= strtolower('{$name}') ?>/create" class="btn btn-primary">Create New</a>
    </div>
    
    <div class="grid gap-4">
        <?php foreach (\$items as \$item): ?>
            <div class="card">
                <!-- Customize based on your model fields -->
                <h3 class="text-lg font-semibold"><?= \$item['id'] ?></h3>
                <div class="mt-4 flex space-x-2">
                    <a href="/<?= strtolower('{$name}') ?>/<?= \$item['id'] ?>" class="btn btn-secondary">View</a>
                    <a href="/<?= strtolower('{$name}') ?>/<?= \$item['id'] ?>/edit" class="btn btn-primary">Edit</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
HTML;
        file_put_contents($viewsPath . "/index.php", $indexTemplate);

        // Create other view files
        $views = ['show', 'create', 'edit'];
        foreach ($views as $view) {
            file_put_contents($viewsPath . "/{$view}.php", "<!-- {$name} {$view} view -->");
        }

        echo "Views created in: {$viewsPath}\n";
    }

    public function getDescription() {
        return "Create a new model (use -mcr flag to create migration, controller, and views)";
    }
}
