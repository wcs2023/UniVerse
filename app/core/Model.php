<?php

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Execute a query and return the statement
     */
    protected function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }

    /**
     * Fetch all results from a query
     */
    protected function fetchAll($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Fetch single result from a query
     */
    protected function fetch($sql, $params = [])
    {
        return $this->db->fetch($sql, $params);
    }

    /**
     * Get the last inserted ID
     */
    protected function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * Insert data into a table
     */
    protected function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }

    /**
     * Update data in a table
     */
    protected function update($table, $data, $where, $whereParams = [])
    {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        
        $params = array_merge($data, $whereParams);
        return $this->db->query($sql, $params);
    }

    /**
     * Delete data from a table
     */
    protected function delete($table, $where, $whereParams = [])
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->db->query($sql, $whereParams);
    }

    protected function first($sql,$param = [])
    {
        return $this->fetch($sql,$param);
    }

}

