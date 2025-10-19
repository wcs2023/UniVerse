<?php

trait Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    /**
     * Constructor - Initialize database connection
     * Sets up database credentials from config constants and establishes connection
     */
    public function __construct()
    {
        $this->host = DBHOST;
        $this->dbname = DBNAME;
        $this->username = DBUSER;
        $this->password = DBPASS;
        
        $this->connect();
    }

    /**
     * Establish PDO database connection
     * Creates a new PDO connection with error handling and sets default attributes
     */
    private function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a prepared SQL query
     * @param string $sql - The SQL query with placeholders
     * @param array $params - Parameters to bind to the query
     * @return PDOStatement - The prepared statement object
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
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
