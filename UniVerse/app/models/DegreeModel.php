<?php

class DegreeModel extends Model
{
    protected $table = 'degrees_cutoff';

    public function searchByFilters($zscore,$stream,$district){
        $query = "SELECT university,course_name,cutoff_marks,unicode,details FROM {$this->table} 
                  WHERE cutoff_marks <= :zscore 
                  AND stream = :stream 
                  AND district = :district
                  ORDER BY cutoff_marks DESC,university ASC,degree_programme ASC";

        $params = [
            'zscore' => $zscore,
            'stream' => $stream,
            'district' => $district
        ];

        return $this->query($query, $params);
    }

}
