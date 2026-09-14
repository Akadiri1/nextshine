<?php

// echo new PDO('NOW()');
// die;
class Database
{
    private $pdo;

    public function __construct($host = DB_HOST, $dbname = DB_NAME, $username = DB_USERNAME, $password = DB_PASSWORD)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Fetch data with mixed AND and OR conditions and pagination
     *
     * @param string $table The table to fetch data from.
     * @param array $columns The columns to select (default: all columns).
     * @param array $conditions An array with keys 'AND' and/or 'OR' containing conditions.
     * @param int|null $limit The number of rows to retrieve (optional).
     * @param int|null $offset The starting point for retrieval (optional).
     * @param string $extra Additional SQL clauses (e.g., ORDER BY).
     * @return array Fetched rows.
     */
    
     public function fetchAll($table, $columns = ['*'], $conditions = null, $limit = null, $offset = null, $groups = null, $order = null, $extra = ''){
        return $this->fetch($table, $columns, $conditions, $limit, $offset, $groups, $order, $extra, true);
    }
    public function fetch($table, $columns = ['*'], $conditions = null, $limit = null, $offset = null, $groups = null, $order = null, $extra = '', $fetch_all = false)
    {
        $orderColumns =  $order['columns'] ?? null;
        $orderType = $order['type'] ?? null;

        if(is_array($table)){
            $param1 = $table;
            $table = $param1['table'];
            $columns = $param1['columns'] ?? $columns ?? ['*'];
            $conditions = $param1['conditions'] ?? $conditions ?? [];
            $limit = $param1['limit'] ?? $limit ?? null;
            $offset = $param1['offset'] ?? $offset ?? null;
            $orderColumns = $param1['order']['columns'] ?? $order['columns'] ?? null;
            $orderType = $param1['order']['type'] ?? $order['type'] ?? null;
            $groups = $param1['groups'] ?? $groups ?? null;
            $extra = $param1['extra'] ?? $extra ?? '';
            $fetch_all = $param1['fetch_all'] ?? $fetch_all ?? false;

        }


        $orderColumns = $orderColumns ?? null;
        $orderType = $orderType ?? null;

        

        if(is_array($table)){
            throw new Exception("Table Name Cannot be an array", 1);
            
        }

        if (is_string($columns)) {
            $columnsList = $columns;
        }else{
            $columnsList = implode(', ', $columns);
        }

        if (!is_null($orderColumns)) {
            if (is_string($orderColumns)) {
                $orderColumnsList = $orderColumns;
            }else{
                $orderColumnsList = implode(', ', $orderColumns);
            }
        }

        if (!is_null($groups)) {
            if (is_string($groups)) {
                $groupsList = $groups;
            }else{
                $groupsList = implode(', ', $groups);
            }
        }

        $sql = "SELECT $columnsList FROM $table";

        // Build the WHERE clause if conditions exist
        $whereClauses = [];
        $values = [];

        if (isset($conditions['AND'])) {
            $andClauses = $this->buildClauses($conditions['AND'], $values);
            $whereClauses[] = '(' . implode(' AND ', $andClauses) . ')';
        }

        if (isset($conditions['OR'])) {
            $orClauses = $this->buildClauses($conditions['OR'], $values);
            $whereClauses[] = '(' . implode(' OR ', $orClauses) . ')';
        }

        if (!isset($conditions['AND']) && !isset($conditions['OR']) && !empty($conditions)) {
            $andClauses = $this->buildClauses($conditions, $values);
            $whereClauses[] = '(' . implode(' AND ', $andClauses) . ')';

        }
        
        

        if (!empty($whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }

        // Add ORDER BY, LIMIT, and OFFSET
        $sql .= " $extra";

        
        if (!is_null($groups)) {
            $sql .= " GROUP BY $groupsList";
        }

        if (!is_null($orderColumns)) {
            $sql .= " ORDER BY $orderColumnsList";
        }

        if(($orderType != null) && ($orderColumns != null)){
            $sql .= " ".strtoupper($orderType);
        }

        if (!is_null($limit)) {
            $sql .= " LIMIT ?";
            $values[] = (int) $limit; // Cast to integer

            if (!is_null($offset)) {
                $sql .= " OFFSET ?";
                $values[] = (int) $offset; // Cast to integer
            }
        }

        // Prepare and execute the query
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($values as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key + 1, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key + 1, $value);
            }
        }
        $stmt->execute();
        if ($fetch_all === true) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    }

    /**
     * Get the count of rows matching conditions.
     *
     * @param string $table The table to count rows in.
     * @param array $conditions An array with keys 'AND' and/or 'OR' containing conditions.
     * @return int The count of rows.
     */
    public function fetchCount($table, $conditions = [])
    {
        $sql = "SELECT COUNT(*) AS count FROM $table";

        // Build the WHERE clause if conditions exist
        $whereClauses = [];
        $values = [];

        if (isset($conditions['AND'])) {
            $andClauses = $this->buildClauses($conditions['AND'], $values);
            $whereClauses[] = '(' . implode(' AND ', $andClauses) . ')';
        }

        if (isset($conditions['OR'])) {
            $orClauses = $this->buildClauses($conditions['OR'], $values);
            $whereClauses[] = '(' . implode(' OR ', $orClauses) . ')';
        }

        if (!isset($conditions['AND']) && !isset($conditions['OR']) && !empty($conditions)) {
            $andClauses = $this->buildClauses($conditions, $values);
            $whereClauses[] = '(' . implode(' AND ', $andClauses) . ')';
        }

        if (!empty($whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }

        // Prepare and execute the query
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        // Fetch and return the count
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function fetchJoin($table, $columns, $joins = [], $conditions = [], $extra = '')
    {
        $columnsList = implode(', ', $columns);
        $sql = "SELECT $columnsList FROM $table";

        foreach ($joins as $join) {
            $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['on']}";
        }

        $whereClauses = [];
        $values = [];

        $andClauses = $this->buildClauses($conditions, $values);
        if (!empty($andClauses)) {
            $sql .= " WHERE " . implode(' AND ', $andClauses);
        }

        $sql .= " $extra";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Build individual clauses for the query.
     *
     * @param array $conditions Array of conditions (e.g., ['name' => 'John']).
     * @param array &$values Reference to bind values for the prepared statement.
     * @return array List of clauses.
     */
    private function buildClauses($conditions, &$values)
    {
        $clauses = [];

        foreach ($conditions as $key => $condition) {
            if (is_array($condition)) {
                if (isset($condition['IN'])) {
                    $placeholders = implode(', ', array_fill(0, count($condition['IN']), '?'));
                    $clauses[] = "$key IN ($placeholders)";
                    $values = array_merge($values, $condition['IN']);
                } elseif (isset($condition['LIKE'])) {
                    $clauses[] = "$key LIKE ?";
                    $values[] = '%' . $condition['LIKE'] . '%';
                }elseif (isset($condition['NOT'])) {
                    $clauses[] = "NOT $key =  ?";
                    $values[] =   $condition['NOT'];
                }
            } else {
                $clauses[] = "$key = ?";
                $values[] = $condition;
            }
        }

        return $clauses;
    }

    /**
    * Insert data into a table.
    *
    * @param string $table The table to insert data into.
    * @param array $data An associative array of column => value pairs to insert.
    * @return int The ID of the inserted row.
    */
    public function insert($table, $data)
    {
        // Separate keys and values
        $columns = implode(', ', array_keys($data));
        
        // Handle NOW() correctly by replacing it directly in the query
        $placeholders = implode(', ', array_map(function ($value) {
            return $value === 'NOW()' ? 'NOW()' : '?';
        }, array_values($data)));

        // Filter out NOW() from data binding
        $filteredValues = array_filter($data, function ($value) {
            return $value !== 'NOW()';
        });

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($filteredValues));

        return $this->pdo->lastInsertId();
    }


    /**
     * Update data in a table.
     *
     * @param string $table The table to update data in.
     * @param array $data An associative array of column => value pairs to update.
     * @param array $conditions An associative array of conditions for the WHERE clause.
     * @return int The number of affected rows.
     */
    public function update($table, $data, $conditions)
    {
        $setClauses = [];
        $values = [];

        foreach ($data as $key => $value) {
            $setClauses[] = "$key = ?";
            $values[] = $value;
        }

        $whereClauses = $this->buildClauses($conditions, $values);
        $sql = "UPDATE $table SET " . implode(', ', $setClauses) . " WHERE " . implode(' AND ', $whereClauses);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->rowCount();
    }

    /**
     * Delete data from a table.
     *
     * @param string $table The table to delete data from.
     * @param array $conditions An associative array of conditions for the WHERE clause.
     * @return int The number of affected rows.
     */
    public function delete($table, $conditions)
    {
        $values = [];
        $whereClauses = $this->buildClauses($conditions, $values);
        $sql = "DELETE FROM $table WHERE " . implode(' AND ', $whereClauses);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->rowCount();
    }


    public function seed($table, array $rows)
    {
        if (empty($rows)) {
            throw new Exception("No data provided for seeding.");
        }

        // Get all unique columns from all rows
        $allColumns = [];
        foreach ($rows as $row) {
            $allColumns = array_merge($allColumns, array_keys($row));
        }
        $columns = array_unique($allColumns);
        $columnsList = implode(', ', $columns);
        
        $values = [];
        $placeholdersArray = [];

        foreach ($rows as $row) {
            $rowPlaceholders = [];
            $rowValues = [];

            foreach ($columns as $column) {
                if (!array_key_exists($column, $row)) {
                    // For missing columns, use NULL or DEFAULT (adjust as needed)
                    $rowPlaceholders[] = 'NULL';
                    continue;
                }

                $value = $row[$column];
                if ($value === 'NOW()') {
                    $rowPlaceholders[] = 'NOW()';
                } else {
                    $rowPlaceholders[] = '?';
                    $rowValues[] = $value;
                }
            }

            $placeholdersArray[] = '(' . implode(', ', $rowPlaceholders) . ')';
            $values = array_merge($values, $rowValues);
        }

        // Construct the SQL query
        $sql = "INSERT INTO $table ($columnsList) VALUES " . implode(', ', $placeholdersArray);

        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            $this->pdo->commit();

            return $stmt->rowCount();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }


    public function handlePDOException(PDOException $e) {
        // Get the SQLSTATE error code
        $errorCode = $e->getCode();

        // Initialize the error message
        $errorMessage = "";

        // Use switch to handle specific error codes
        switch ($errorCode) {
            case '1049': // Unknown database
                $errorMessage = "Database does not exist. Please check the database name.";
                break;
            case '2002': // Connection refused or host not found
                $errorMessage = "Unable to connect to the database server. Please check the host and port.";
                break;
            case '1045': // Access denied (invalid user/password)
                $errorMessage = "Access denied. Please check your username and password.";
                break;
            case '42S02': // Table not found
                $errorMessage = "Table not found. Please check the table name in the query.";
                break;
            case '23000': // Integrity constraint violation
                $errorMessage = "Integrity constraint violation. Please check the data being inserted or updated.";
                break;
            case 'HY000': // General SQL error
                $errorMessage = "General SQL error. Please verify your SQL syntax and data.";
                break;
            case '42000': // Syntax error or access violation
                $errorMessage = "Syntax error or access violation. Please review your query syntax.";
                break;
            case '28000': // Invalid authorization specification
                $errorMessage = "Invalid authorization specification. Please check your credentials and permissions.";
                break;
            case '22001': // String data, right truncation
                $errorMessage = "String data is too long for the field. Please adjust the input size.";
                break;
            case '22007': // Invalid datetime format
                $errorMessage = "Invalid datetime format. Please ensure the date/time values are correct.";
                break;
            case '42S22': // Column not found
                $errorMessage = "Column not found. Please check your query for correct column names.";
                break;
            default:
                // Generic error message for unknown codes
                $errorMessage = "An error occurred: " . $e->getMessage();
                break;
        }

        // Log the error (optional: replace with actual logging mechanism)
        error_log("PDO Error [Code: $errorCode]: $errorMessage");

        // Return the error message
        return $errorMessage;
    }

    public function setConnection($host = DB_HOST, $username = DB_USERNAME, $password = DB_PASSWORD, $dbname = null)
    {
        try {
            if ($dbname) {
                $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            } else {
                $this->pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
            }
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getDB()
    {
        return $this->pdo;
    }
    

    public function getConnection()
    {
        return $this->pdo;
    }

    public function close()
    {
        $this->pdo = null;
    }


    public function __destruct()
    {
        $this->pdo = null;
    }
}

