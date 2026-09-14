<?php

if (!function_exists('array_key_last')) {
    function array_key_last(array $array) {
        if (empty($array)) {
            return null;
        }
        end($array);
        return key($array);
    }
  }
  
  
class TableBuilder {
    protected $tableName;
    protected $columns = [];
    protected $foreignKeys = [];
    protected $indexes = [];

    public function __construct($tableName) {
        $this->tableName = $tableName;
    }

    public function id() {
        $this->columns['id'] = 'INT AUTO_INCREMENT PRIMARY KEY';
    }

    public function string($name, $length = 255) {
        $this->columns[$name] = "VARCHAR($length)";
        return $this;
    }
    
    public function char($name, $length = 255) {
        $this->columns[$name] = "CHAR($length)";
        return $this;
    }

    public function integer($name) {
        $this->columns[$name] = "INT";
        return $this;
    }
    public function decimal($name, $precision = 10, $scale = 2) {
        $this->columns[$name] = "DECIMAL($precision, $scale)";
        return $this;
    }

    public function text($name) {
        $this->columns[$name] = "TEXT";
        return $this;
    }

    public function longText($name) {
        $this->columns[$name] = "LONGTEXT";
        return $this;
    }
    
    public function json($name) {
        $this->columns[$name] = "JSON";
        return $this;
    }

    public function timestamp($name) {
        $this->columns[$name] = "TIMESTAMP";
        return $this;
    }

    public function time($name){
        $this->columns[$name] = "TIME";
        return $this;
    }

    public function date($name){
        $this->columns[$name] = "DATE";
        return $this;
    }

    public function default($value) {
        $lastColumn = array_key_last($this->columns);
        if (!$lastColumn) {
            throw new InvalidArgumentException("No column defined to set default value.");
        }
        if (strpos($this->columns[$lastColumn], 'TIMESTAMP') !== false && strtoupper($value) === 'CURRENT_TIMESTAMP') {
            $this->columns[$lastColumn] .= " DEFAULT CURRENT_TIMESTAMP";
        } elseif (strpos($this->columns[$lastColumn], 'TEXT') !== false) {
            throw new InvalidArgumentException("TEXT columns cannot have default values in MySQL.");
        } else {
            $this->columns[$lastColumn] .= " DEFAULT " . (is_string($value) ? "'$value'" : $value);
        }
        return $this;
    }

    public function nullable() {
        $lastColumn = array_key_last($this->columns);
        if ($lastColumn) {
            $this->columns[$lastColumn] .= " NULL";
        }
        return $this;
    }

    public function unique($columns = null, $indexName = null) {
        if (empty($columns)) {
            // Get the last added column if no column is specified
            $columns = array_key_last($this->columns);
            if (!$columns) {
                throw new InvalidArgumentException("No column defined to create a unique constraint.");
            }
            $columns = [$columns]; // Convert to an array for consistency
        }
    
        // Ensure $columns is always an array
        $columns = (array)$columns;
    
        // Generate the index name if not provided
        $indexName = $indexName ?? implode('_', $columns) . '_unique';
    
        // Prepare the SQL for the unique constraint
        $columnsList = implode('`, `', $columns);
        $this->indexes[] = "UNIQUE `$indexName` (`$columnsList`)";
        return $this;
    }
    

    public function index($columns, $indexName = null) {
        $columns = (array)$columns;
        $indexName = $indexName ?? implode('_', $columns) . '_index';
        $columnsList = implode('`, `', $columns);
        $this->indexes[] = "INDEX `$indexName` (`$columnsList`)";
        return $this;
    }

    public function foreign($column, $references, $onTable, $onDelete = null, $onUpdate = null) {
        if (!isset($this->columns[$column])) {
            throw new InvalidArgumentException("The column `$column` does not exist in the table schema.");
        }
        $constraint = "FOREIGN KEY (`$column`) REFERENCES `$onTable`(`$references`)";
        if ($onDelete) {
            $constraint .= " ON DELETE $onDelete";
        }
        if ($onUpdate) {
            $constraint .= " ON UPDATE $onUpdate";
        }
        $this->foreignKeys[] = $constraint;
        return $this;
    }
    

    public function getColumns() {
        return $this->columns;
    }


    public function unsigned() {
        $lastColumn = array_key_last($this->columns);
        if ($lastColumn) {
            $this->columns[$lastColumn] .= " UNSIGNED";
        }
        return $this;
    }
    public function getSql() {
        $columnsSql = [];
        foreach ($this->columns as $name => $definition) {
            $columnsSql[] = "`$name` $definition";
        }
        $foreignKeysSql = $this->foreignKeys ? [$this->foreignKeys] : [];
        $indexesSql = $this->indexes ? [$this->indexes] : [];
    
        // Flatten all definitions
        $allDefinitions = array_merge($columnsSql, ...$foreignKeysSql, ...$indexesSql);
    
        $definitionsString = implode(", ", $allDefinitions);
    
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableName}` ($definitionsString) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        print_r($sql);
        return $sql;

    }
    
}
