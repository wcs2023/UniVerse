<?php 

class DegreeModel extends Model{
    protected $table = "degree_cutoffs";

    public function getDegreeRecommendations($zscore,$district,$stream){

        $query = 'SELECT 
                u.university_name,
                u.university_code,
                dp.degree_id,
                dp.unicode,
                dp.details,
                dc.cutoff_mark
                
                FROM degree_cutoffs dc
                JOIN degree_programs dp ON dc.degree_id = dp.degree_id
                JOIN universities u ON dp.university_id = u.university_id
                WHERE dc.cutoff_mark <= :zscore
                    AND dp.stream = :stream
                    AND dc.district = :district
                    ORDER BY dc.cutoff_mark DESC';

                    return $this ->fetchAll($query,['zscore' =>$zscore,'district'=>$district,'stream' =>$stream]);

                    
    }
}