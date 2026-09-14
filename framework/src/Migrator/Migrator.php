<?php
namespace App\Migrator;

class Migrator {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), batch INT, executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    }

    public function run() {
        echo "Running migrations...\n";
        $executed = $this->pdo->query("SELECT migration FROM migrations")->fetchAll(\PDO::FETCH_COLUMN);
        $files = glob(dirname(__DIR__, 2) . '/../database/migrations/*.php');
        sort($files); // Ensure chronological order
        $batch = ($this->pdo->query("SELECT MAX(batch) FROM migrations")->fetchColumn() ?: 0) + 1;

        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, $executed)) continue;

            $migrationObject = require_once $file;
            if (is_object($migrationObject) && method_exists($migrationObject, 'up')) {
                $migrationObject->up($this);
                $stmt = $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
                $stmt->execute([$name, $batch]);
                $this->log("Migrated: $name");
            }
        }
    }

    public function table($name, $callback) {
        $query = $this->pdo->query("SHOW TABLES LIKE '$name'");
        $tableExists = $query->rowCount() > 0;
        
        $existingColumns = $tableExists ? $this->pdo->query("DESCRIBE `$name`")->fetchAll(\PDO::FETCH_COLUMN) : [];

        $blueprint = new Blueprint($name, $existingColumns);
        $callback($blueprint);

        if (!$tableExists) {
            // --- CREATE TABLE ---
            $definitions = [];
            foreach ($blueprint->getColumns() as $col) {
                $definitions[] = preg_replace('/AFTER\s+`[^`]+`\s*/i', '', $col['definition']);
            }

            // Include indexes in the initial CREATE statement
            $definitions = array_merge($definitions, $blueprint->getIndexes());

            $sql = "CREATE TABLE `$name` (" . implode(", ", $definitions) . ") ENGINE=InnoDB;";
            $this->pdo->exec($sql);
        } else {
            // 1. Handle Renaming (MySQL 8.0+)
            foreach ($blueprint->getColumnsToRename() as $old => $new) {
                if (in_array($old, $existingColumns)) {
                    $this->pdo->exec("ALTER TABLE `$name` RENAME COLUMN `$old` TO `$new` ");
                    $this->log("Renamed column $old to $new");
                }
            }

            // 2. Handle Changing/Modifying Types
            foreach ($blueprint->getColumnsToChange() as $colName => $definition) {
                if (in_array($colName, $existingColumns)) {
                    // Strips out 'AFTER' clauses if present for MODIFY
                    $cleanDef = preg_replace('/AFTER\s+`[^`]+`\s*/i', '', $definition);
                    $this->pdo->exec("ALTER TABLE `$name` MODIFY COLUMN $cleanDef");
                    $this->log("Modified column: $colName");
                }
            }

            // 3. Handle Drops
            foreach ($blueprint->getColumnsToDrop() as $drop) {
                if (in_array($drop, $existingColumns)) {
                    $this->pdo->exec("ALTER TABLE `$name` DROP COLUMN `$drop` ");
                    $this->log("Dropped column: $drop");
                }
            }

            // 4. Handle Adds
            foreach ($blueprint->getColumns() as $col) {
                if (!in_array($col['name'], $existingColumns)) {
                    $this->pdo->exec("ALTER TABLE `$name` ADD {$col['definition']}");
                    $this->log("Added column: {$col['name']}");
                }
            }
        }
    }
    

    public function insert($table, $data) {
        $keys = array_keys($data);
        $fields = implode('`, `', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));
        $sql = "INSERT INTO `$table` (`$fields`) VALUES ($placeholders)";
        $this->pdo->prepare($sql)->execute(array_values($data));
    }

    public function upsert($table, $data, $uniqueKey = 'id') {
        $keys = array_keys($data);
        $fields = implode('`, `', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));
        $updates = array_map(fn($k) => "`$k` = VALUES(`$k`)", $keys);
        $sql = "INSERT INTO `$table` (`$fields`) VALUES ($placeholders) ON DUPLICATE KEY UPDATE " . implode(', ', $updates);
        $this->pdo->prepare($sql)->execute(array_values($data));
    }

    /**
     * Update rows matching every column in $where, for data migrations.
     * The where clause is required so a migration can never rewrite a whole table.
     */
    public function update($table, $data, $where) {
        if (empty($data) || empty($where)) {
            throw new \InvalidArgumentException("update() on `$table` needs both data and a where clause.");
        }

        $set  = implode(', ', array_map(function ($k) { return "`$k` = ?"; }, array_keys($data)));
        $cond = implode(' AND ', array_map(function ($k) { return "`$k` = ?"; }, array_keys($where)));

        $stmt = $this->pdo->prepare("UPDATE `$table` SET $set WHERE $cond");
        $stmt->execute(array_merge(array_values($data), array_values($where)));
        $this->log("Updated {$stmt->rowCount()} row(s) in $table");
    }

    /**
     * Delete rows matching every column in $where, for data migrations.
     * The where clause is required so a migration can never empty a table.
     */
    public function delete($table, $where) {
        if (empty($where)) {
            throw new \InvalidArgumentException("delete() on `$table` needs a where clause.");
        }

        $cond = implode(' AND ', array_map(function ($k) { return "`$k` = ?"; }, array_keys($where)));

        $stmt = $this->pdo->prepare("DELETE FROM `$table` WHERE $cond");
        $stmt->execute(array_values($where));
        $this->log("Deleted {$stmt->rowCount()} row(s) from $table");
    }

    public function fresh() {
        // Indicate what will happen clearly
        echo "\n" . str_repeat('!', 40) . "\n";
        echo " WARNING: DESTRUCTIVE ACTION DETECTED\n";
        echo " This will DROP ALL TABLES in your database.\n";
        echo " All data will be permanently lost.\n";
        echo str_repeat('!', 40) . "\n\n";
        
        echo "Are you sure you want to proceed? (yes/no): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        switch (trim(strtolower($line))) {
            case 'yes':
            case 'y':
                break;
            default:
                echo "Operation cancelled.\n";
                return;
        }

        echo "Dropping all tables...\n";
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $tables = $this->pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE `$table` ");
            $this->log("Dropped: $table");
        }
        
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        // Re-create migration tracking table
        $this->pdo->exec("CREATE TABLE migrations (
            id INT AUTO_INCREMENT PRIMARY KEY, 
            migration VARCHAR(255), 
            batch INT, 
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $this->run();
    }

    public function rollback() {
        $lastBatch = $this->pdo->query("SELECT MAX(batch) FROM migrations")->fetchColumn();
        if (!$lastBatch) return;
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations WHERE batch = ? ORDER BY id DESC");
        $stmt->execute([$lastBatch]);
        foreach ($stmt->fetchAll(\PDO::FETCH_COLUMN) as $name) {
            $file = dirname(__DIR__, 2) . '/../database/migrations/' . $name;
            if (file_exists($file)) {
                $migrationObject = require $file;
                $migrationObject->down($this);
                $this->pdo->prepare("DELETE FROM migrations WHERE migration = ?")->execute([$name]);
                $this->log("Rolled back: $name");
            }
        }
    }

    public function dropTable($name) { $this->pdo->exec("DROP TABLE IF EXISTS `$name` "); }
    private function log($m) { echo "[Migration] $m \n"; }
}