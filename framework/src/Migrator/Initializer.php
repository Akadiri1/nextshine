<?php
namespace App\Migrator;

abstract class Initializer {
    protected $migrator;
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->migrator = new Migrator($pdo);
    }

    /**
     * Your custom logic goes here
     */
    abstract public function setup();

    /**
     * Static runner for the mck script
     */
    public static function run($pdo) {
        $class = 'App\\Migrator\\ProjectInitializer';
        if (class_exists($class)) {
            (new $class($pdo))->setup();
        } else {
            echo "Error: ProjectInitializer class not found.\n";
        }
    }
}