<?php

namespace DzWork\Core\Console\Commands;

class MakeControllerCommand {
    public function execute($args) {
        if (empty($args)) {
            echo "Controller name required\n";
            return;
        }

        $name = $args[0];
        $this->createController($name);
    }

    protected function createController($name) {
        if (!str_ends_with($name, 'Controller')) {
            $name .= 'Controller';
        }

        $template = <<<PHP
<?php

namespace App\Controllers;

use DzWork\Core\Controller;
use DzWork\Core\Response;

class {$name} extends Controller {
    public function index() {
        return Response::view('index');
    }

    public function show(\$id) {
        return Response::view('show', ['id' => \$id]);
    }

    public function create() {
        return Response::view('create');
    }

    public function store(\$request) {
        \$data = \$request->input();
        return Response::json(['message' => 'Created successfully', 'data' => \$data]);
    }

    public function edit(\$id) {
        return Response::view('edit', ['id' => \$id]);
    }

    public function update(\$request, \$id) {
        \$data = \$request->input();
        return Response::json(['message' => 'Updated successfully', 'data' => \$data]);
    }

    public function destroy(\$id) {
        return Response::json(['message' => 'Deleted successfully']);
    }
}
PHP;

        $path = APP_PATH . "/Controllers/{$name}.php";
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        
        file_put_contents($path, $template);
        echo "Controller created: {$path}\n";
    }

    public function getDescription() {
        return "Create a new controller";
    }
}
