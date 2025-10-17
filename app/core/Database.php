<?php

class Database
{
    private static $instance = null;
    private $conn;
    
    private function __construct()
    {
        $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME;
        $this->conn = new PDO($string, DBUSER, DBPASS);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->conn;
    }
    
    /**
     * Execute a SQL query directly (for schema changes, etc.)
     * 
     * @param string $sql The SQL query to execute
     * @return bool True on success, false on failure
     */
    public function executeSQL($sql)
    {
        try {
            $this->conn->exec($sql);
            return true;
        } catch(PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Execute a SQL file
     * 
     * @param string $filePath Path to the SQL file
     * @return bool True on success, false on failure
     */
    public function executeSQLFile($filePath)
    {
        if (!file_exists($filePath)) {
            error_log("SQL file not found: $filePath");
            return false;
        }
        
        try {
            $sql = file_get_contents($filePath);
            // Split SQL file into individual queries
            $queries = array_filter(array_map('trim', explode(';', $sql)), 'strlen');
            
            // Begin transaction
            $this->conn->beginTransaction();
            
            foreach ($queries as $query) {
                $this->conn->exec($query);
            }
            
            // Commit transaction
            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            // Rollback transaction on error
            $this->conn->rollBack();
            error_log("Database error executing SQL file: " . $e->getMessage());
            return false;
        }
    }
}