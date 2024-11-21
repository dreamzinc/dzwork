<?php

namespace DzWork\Core\Console\Commands;

class MakeViewCommand {
    public function execute($args) {
        if (empty($args)) {
            echo "View name required\n";
            return;
        }

        $name = $args[0];
        $this->createView($name);
    }

    protected function createView($name) {
        $template = <<<HTML
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">{$name}</h1>
    
    <div class="card">
        <!-- Add your content here -->
    </div>
</div>
HTML;

        $path = APP_PATH . "/Views/" . str_replace('.', '/', $name) . ".php";
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        
        file_put_contents($path, $template);
        echo "View created: {$path}\n";
    }

    public function getDescription() {
        return "Create a new view";
    }
}
