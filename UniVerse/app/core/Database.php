<?php

class Database
{
    private static $instance = null;
    private $pdo;

    /**
     * Get the singleton instance of the Database class
     * @return Database - The instance of the Database class
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor - Initialize database connection
     * Sets up database credentials from config constants and establishes connection
     */
    private function __construct()
    {
        try {
            // Use constants from config.php instead of requiring a separate file
            $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
            
            $this->pdo = new PDO(
                $dsn,
                DBUSER,
                DBPASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get the PDO connection instance
     * @return PDO - The PDO database connection
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * Execute a prepared SQL query
     * @param string $sql - The SQL query with placeholders
     * @param array $params - Parameters to bind to the query
     * @return PDOStatement - The prepared statement object
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Execute query and fetch all results as associative array
     * @param string $sql - The SQL query
     * @param array $params - Parameters to bind
     * @return array - Array of all matching rows
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Execute query and fetch single result as associative array
     * @param string $sql - The SQL query
     * @param array $params - Parameters to bind
     * @return array|false - Single row or false if no results
     */
    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Get the ID of the last inserted row
     * Useful after INSERT operations to get the auto-generated ID
     * @return string - The last insert ID
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}