<?php

namespace DzWork\Core\Console;

class Application {
    protected $commands = [];
    protected $defaultCommands = [
        'serve' => Commands\ServeCommand::class,
        'make:controller' => Commands\MakeControllerCommand::class,
        'make:model' => Commands\MakeModelCommand::class,
        'make:migration' => Commands\MakeMigrationCommand::class,
        'make:view' => Commands\MakeViewCommand::class,
        'migrate' => Commands\MigrateCommand::class,
        'migrate:rollback' => Commands\MigrateRollbackCommand::class,
    ];

    public function __construct() {
        $this->registerDefaultCommands();
    }

    protected function registerDefaultCommands() {
        foreach ($this->defaultCommands as $name => $class) {
            $this->commands[$name] = new $class();
        }
    }

    public function run() {
        global $argv;
        
        if (!isset($argv[1])) {
            $this->showHelp();
            return;
        }

        $command = $argv[1];
        $args = array_slice($argv, 2);

        if (isset($this->commands[$command])) {
            $this->commands[$command]->execute($args);
        } else {
            echo "Command not found: {$command}\n";
            $this->showHelp();
        }
    }

    protected function showHelp() {
        echo "DzWork Framework CLI\n\n";
        echo "Available commands:\n";
        foreach ($this->commands as $name => $command) {
            echo "  {$name}: " . $command->getDescription() . "\n";
        }
    }
}
