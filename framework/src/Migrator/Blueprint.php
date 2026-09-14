<?php
namespace App\Migrator;

class Blueprint {
    protected $table;
    protected $columns = [];
    protected $indexes = [];
    protected $columnsToDrop = [];
    protected $columnsToRename = [];
    protected $columnsToChange = [];
    protected $existingColumns = [];
    protected $currentColumn = null;

    public function __construct($table, $existingColumns = []) { 
        $this->table = $table; 
        $this->existingColumns = $existingColumns;
    }

    // --- Primary Key & Numeric Types ---
    public function id($name = 'id') { 
        return $this->addColumn($name, "INT AUTO_INCREMENT PRIMARY KEY"); 
    }

    public function integer($name) { 
        return $this->addColumn($name, "INT"); 
    }

    public function bigInt($name) {
        return $this->addColumn($name, "BIGINT");
    }

    public function decimal($name, $precision = 8, $scale = 2) {
        return $this->addColumn($name, "DECIMAL($precision, $scale)");
    }

    // --- String & Text Types ---
    public function string($name, $len = 255) { 
        return $this->addColumn($name, "VARCHAR($len)"); 
    }

    public function text($name) { 
        return $this->addColumn($name, "TEXT"); 
    }

    public function longText($name) {
        return $this->addColumn($name, "LONGTEXT");
    }

    // --- Specialized Types ---
    public function boolean($name) { 
        return $this->addColumn($name, "TINYINT(1)"); 
    }

    public function timestamp($name) {
        return $this->addColumn($name, "TIMESTAMP");
    }

    // --- Date & Time Types ---
    // ADMC keeps date_created / time_created as DATE and TIME, and
    // insertContent() writes 'Y-m-d' and 'H:i:s' into them, which a
    // TIMESTAMP column rejects.
    public function date($name) {
        return $this->addColumn($name, "DATE");
    }

    public function time($name) {
        return $this->addColumn($name, "TIME");
    }

    public function dateTime($name) {
        return $this->addColumn($name, "DATETIME");
    }

    /**
     * The bookkeeping columns every ADMC-managed table carries
     * (ADMC_framework.md, section 2.2). Declare id() and hash_id first,
     * then call this last.
     */
    public function admcColumns() {
        $this->string('visibility', 50)->default('show');
        $this->date('date_created');
        $this->time('time_created');
        $this->string('created_by')->default('');
        return $this;
    }

    public function timestamps($type = 1) {
        if (!$type === 2) {
            // Using NULLable timestamps is safer for various MySQL SQL_MODES
            $this->addColumn('created_at', 'TIMESTAMP NULL')->default('CURRENT_TIMESTAMP');
            $this->addColumn('updated_at', 'TIMESTAMP NULL')->default('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
            return $this;
        }else{
            $this->addColumn('time_created', 'TIMESTAMP')->default('CURRENT_TIMESTAMP');
            $this->addColumn('date_created', 'TIMESTAMP')->default('CURRENT_TIMESTAMP');
            return $this;
        }
    }

    // --- Fluent Modifiers ---
    public function nullable() {
        $this->updateCurrentColumn("NULL");
        return $this;
    }

    public function default($value) {
        // Wraps string defaults in quotes, but ignores MySQL keywords like CURRENT_TIMESTAMP
        $val = is_string($value) && !preg_match('/CURRENT_TIMESTAMP|ON UPDATE/i', $value) ? "'$value'" : $value;
        $this->updateCurrentColumn("DEFAULT $val");
        return $this;
    }

    public function after($column) {
        $this->updateCurrentColumn("AFTER `$column` ");
        return $this;
    }

    // --- Indexes & Constraints ---
    public function unique($name = null) {
        $colName = $this->currentColumn['name'];
        $indexName = $name ?? "uni_{$this->table}_{$colName}";
        $this->indexes[] = "UNIQUE INDEX `$indexName` (`$colName`)";
        return $this;
    }

    public function index($name = null) {
        $colName = $this->currentColumn['name'];
        $indexName = $name ?? "idx_{$this->table}_{$colName}";
        $this->indexes[] = "INDEX `$indexName` (`$colName`)";
        return $this;
    }

    public function foreign($column) {
        // Set current column context for fluent references
        $this->currentColumn = ['name' => $column]; 
        return $this;
    }

    public function references($column) {
        $this->currentColumn['references'] = $column;
        return $this;
    }

    public function on($table) {
        $colName = $this->currentColumn['name'];
        $refCol = $this->currentColumn['references'];
        $this->indexes[] = "FOREIGN KEY (`$colName`) REFERENCES `$table`(`$refCol`) ON DELETE CASCADE";
        return $this;
    }

    // --- Maintenance ---
    /**
     * Rename a column: renameColumn('old_name', 'new_name')
     */
    public function renameColumn($from, $to) {
        $this->columnsToRename[$from] = $to;
        return $this;
    }

    /**
     * Change a column's type or attributes: string('name')->change()
     */
    public function change() {
        if ($this->currentColumn) {
            $name = $this->currentColumn['name'];
            $this->columnsToChange[$name] = $this->currentColumn['definition'];
            // Remove from standard columns so it doesn't trigger an 'ADD'
            unset($this->columns[$name]);
        }
        return $this;
    }
    public function dropColumn($name) {
        $this->columnsToDrop[] = $name;
        return $this;
    }

    // --- Internal Logic ---
    public function getColumnsToRename() { return $this->columnsToRename; }
    public function getColumnsToChange() { return $this->columnsToChange; }
    public function getColumnsToDrop() { return $this->columnsToDrop; }
    public function getColumns() { return $this->columns; }
    public function getIndexes() { return $this->indexes; }

    // protected function addColumn($name, $definition) {
    //     $this->columns[$name] = ['name' => $name, 'definition' => "`$name` $definition"];
    //     if (!str_contains(strtoupper($definition), 'NULL')) {
    //          $this->columns[$name]['definition'] .= " NOT NULL";
    //     }
    //     $this->currentColumn = &$this->columns[$name];
    //     return $this;
    // }
    protected function addColumn($name, $definition) {
        $nullPart = (str_contains(strtoupper($definition), 'NULL')) ? "" : " NOT NULL";
        
        $this->columns[$name] = [
            'name' => $name, 
            'definition' => "`$name` $definition$nullPart"
        ];
        
        $this->currentColumn = &$this->columns[$name];
        return $this;
    }

    // protected function updateCurrentColumn($suffix) {
    //     if ($this->currentColumn) {
    //         // If the user calls ->nullable(), we strip the default NOT NULL added by addColumn
    //         if (str_contains(strtoupper($suffix), "NULL") && str_contains($this->currentColumn['definition'], "NOT NULL")) {
    //             $this->currentColumn['definition'] = str_replace(" NOT NULL", "", $this->currentColumn['definition']);
    //         }
    //         $this->currentColumn['definition'] .= " $suffix";
    //     }
    // }
    
    protected function updateCurrentColumn($suffix) {
        if ($this->currentColumn) {
            // 1. If we are setting a column to NULL, remove the default 'NOT NULL'
            if (stripos($suffix, "NULL") !== false && !stripos($suffix, "NOT NULL")) {
                $this->currentColumn['definition'] = str_ireplace(" NOT NULL", "", $this->currentColumn['definition']);
            }
            
            // 2. Append the new modifier
            $this->currentColumn['definition'] .= " $suffix";
            
            // 3. Fix order: AFTER must always be at the very end of the column definition
            if (stripos($this->currentColumn['definition'], " AFTER ") !== false) {
                preg_match('/AFTER\s+`[^`]+`\s*/i', $this->currentColumn['definition'], $matches);
                if (!empty($matches)) {
                    // Remove the AFTER clause from where it is and move it to the end
                    $cleanDef = str_replace($matches[0], "", $this->currentColumn['definition']);
                    $this->currentColumn['definition'] = rtrim($cleanDef) . " " . trim($matches[0]);
                }
            }
        }
    }
}