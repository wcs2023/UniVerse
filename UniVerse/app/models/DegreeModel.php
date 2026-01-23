<?php

class DegreeModel extends Model
{
    protected $table = 'degrees_cutoff';

    public function searchByFilters($zscore,$stream,$district){
        $query = "SELECT university,course_name,cutoff_marks,unicode,details FROM {$this->table} 
                  WHERE cutoff_marks <= :zscore 
                  AND stream = :stream 
                  AND district = :district
                  
                  ORDER BY cutoff_marks DESC,university ASC,course_name ASC";

        $params = [
            'zscore' => (float)$zscore,
            'stream' => strtolower($stream),
            'district' => strtolower($district),
        ];

        return $this->fetchAll($query, $params);
    }

    public function findById(int $id)
    {
        $sql = "SELECT id, unicode, university, course_name, cutoff_marks, details
                FROM {$this->table}
                WHERE id = :id
                -- LIMIT 1";
        $rows = $this->query($sql, ['id' => $id]);
        return $rows ? $rows[0] : null;
    }
    
}