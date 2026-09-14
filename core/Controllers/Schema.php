<?php
class Schema {
    protected static $pdo;
    protected static $dbName;

    public static function init($pdo, $dbName) {
        self::$pdo = $pdo;
        self::$dbName = $dbName;

        // Check and create database if it doesn't exist
        self::createDatabaseIfNotExists($dbName);
    }

    protected static function createDatabaseIfNotExists($dbName) {
        try {
            $stmt = self::$pdo->query("USE `$dbName`");
        } catch (PDOException $e) {
            // Database does not exist, create it
            self::$pdo->exec("CREATE DATABASE `$dbName`");
            echo "Database `$dbName` created successfully.\n";

            // Switch to the newly created database
            self::$pdo->exec("USE `$dbName`");
        }
    }

    public static function migrate($tableName, $callback) {
        // Check if the table exists
        $tableExists = self::tableExists($tableName);

        if ($tableExists) {
            // Update the table
            $table = new TableBuilder($tableName);
            $callback($table);
            self::updateTable($tableName, $table->getColumns());
        } else {
            // Create the table
            self::create($tableName, $callback);
        }
    }

    public static function create($tableName, $callback) {
        $table = new TableBuilder($tableName);
        $callback($table);
        $sql = $table->getSql();
        self::$pdo->exec($sql);
        echo "Table `$tableName` created successfully.\n";
    }

    protected static function tableExists($tableName) {
        $stmt = self::$pdo->prepare("SHOW TABLES LIKE :table");
        $stmt->execute(['table' => $tableName]);
        return $stmt->rowCount() > 0;
    }

    protected static function updateTable($tableName, $columns) {
        $existingColumns = self::getExistingColumns($tableName);
    
        foreach ($columns as $column => $definition) {
            // Skip modifying columns if they are primary keys
            if (!array_key_exists($column, $existingColumns)) {
                self::addColumn($tableName, $column, $definition);
            } elseif (strpos(strtolower($definition), 'primary key') === false &&
                      strtolower(trim($existingColumns[$column])) !== strtolower(trim($definition))) {
                self::modifyColumn($tableName, $column, $definition);
            }
        }
    }
    

    protected static function getExistingColumns($tableName) {
        $stmt = self::$pdo->query("SHOW FULL COLUMNS FROM `$tableName`");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $existingColumns = [];
        foreach ($columns as $column) {
            $definition = $column['Type'];

            if ($column['Default'] === 'CURRENT_TIMESTAMP') {
                $definition .= " DEFAULT CURRENT_TIMESTAMP";
            }

            if ($column['Null'] === 'NO') {
                $definition .= " NOT NULL";
            }

            if ($column['Key'] === 'PRI') {
                $definition .= " PRIMARY KEY";
            }

            $existingColumns[$column['Field']] = $definition;
        }

        return $existingColumns;
    }

    protected static function addColumn($tableName, $column, $definition) {
        $sql = "ALTER TABLE `$tableName` ADD `$column` $definition";
        self::$pdo->exec($sql);
        echo "Column `$column` added to `$tableName`.\n";
    }

    protected static function modifyColumn($tableName, $column, $definition) {
        $sql = "ALTER TABLE `$tableName` MODIFY `$column` $definition";
        self::$pdo->exec($sql);
        echo "Column `$column` modified in `$tableName`.\n";
    }
}
