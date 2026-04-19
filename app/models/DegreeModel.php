<?php 

class DegreeModel extends Model{
    protected $table = "degree_cutoffs";

    public function getDegreeRecommendations($zscore, $district, $stream)
    {
        $query = "SELECT
                    dp.degree_id AS degree_id,
                    dp.unicode AS unicode,
                    dp.details AS details,
                    dp.degree_name AS degree_name,
                    dc.cutoff_mark AS cutoff_mark,
                    u.university_name AS university_name,
                    u.university_code AS university_code
                FROM degree_cutoffs dc
                JOIN degree_programs dp ON dc.degree_id = dp.degree_id
                LEFT JOIN universities u ON dp.university_id = u.university_id
                WHERE dc.cutoff_mark <= :zscore
                    AND LOWER(TRIM(dp.stream)) = LOWER(TRIM(:stream))
                    AND LOWER(TRIM(dc.district)) = LOWER(TRIM(:district))
                ORDER BY dc.cutoff_mark DESC";

        return $this->fetchAll($query, [
            'zscore'   => (float)$zscore,
            'district' => $district,
            'stream'   => $stream
        ]);
    }
} 