<?php
namespace App\Migrator;

abstract class Seeder {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    abstract public function run();

    /**
     * Updated Static Runner: Handles all or a specific seeder.
     */
    public static function runAll($pdo, $specificSeeder = null) {
        $path = dirname(__DIR__, 2) . '/../database/seeds/';
        
        if ($specificSeeder) {
            $files = [$path . $specificSeeder . '.php'];
        } else {
            $files = glob($path . '*.php');
        }

        if (empty($files) || ($specificSeeder && !file_exists($files[0]))) {
            echo "No seeder(s) found.\n";
            return;
        }

        foreach ($files as $file) {
            if (file_exists($file)) {
                require_once $file;
                $class = pathinfo($file, PATHINFO_FILENAME);
                if (class_exists($class)) {
                    (new $class($pdo))->run();
                    echo "[Seed] Success: $class\n";
                }
            }
        }
    }

    protected function insert($table, $data) {
        $keys = array_keys($data);
        $fields = implode('`, `', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));
        $sql = "INSERT INTO `$table` (`$fields`) VALUES ($placeholders)";
        $this->pdo->prepare($sql)->execute(array_values($data));
    }

    protected function upsert($table, $data, $uniqueKey = 'id') {
        $keys = array_keys($data);
        $fields = implode('`, `', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));
        $updates = array_map(fn($k) => "`$k` = VALUES(`$k`)", $keys);
        $sql = "INSERT INTO `$table` (`$fields`) VALUES ($placeholders) ON DUPLICATE KEY UPDATE " . implode(', ', $updates);
        $this->pdo->prepare($sql)->execute(array_values($data));
    }

    /**
     * Insert each row unless one with the same $key value already exists.
     *
     * Unlike upsert() this needs no unique index, and it never overwrites a
     * row an admin has since edited, so seeding a live database is safe.
     */
    protected function insertMissing($table, array $rows, $key = 'hash_id') {
        $exists = $this->pdo->prepare("SELECT COUNT(*) FROM `$table` WHERE `$key` = ?");
        $inserted = 0;

        foreach ($rows as $row) {
            $exists->execute([$row[$key]]);
            if ((int) $exists->fetchColumn() > 0) {
                continue;
            }
            $this->insert($table, $this->withAdmcColumns($row));
            $inserted++;
        }

        echo "  $table: $inserted of " . count($rows) . " inserted\n";
    }

    /**
     * Seed a single-row settings_ table, but only while it is still empty.
     */
    protected function insertIfEmpty($table, array $row) {
        $empty = (int) $this->pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn() === 0;

        if ($empty) {
            $this->insert($table, $this->withAdmcColumns($row));
        }

        echo "  $table: " . ($empty ? 1 : 0) . " of 1 inserted\n";
    }

    /**
     * Fill in the ADMC bookkeeping columns a row does not set itself.
     */
    protected function withAdmcColumns(array $row) {
        return $row + [
            'visibility'   => 'show',
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'created_by'   => 'system',
        ];
    }
}