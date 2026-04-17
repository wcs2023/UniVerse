<?php

class DegreeCutoff extends Model
{
    /**
     * Import cutoff marks from CSV
     */
    public function importFromCsv($fileTmpPath)
    {
        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        if (!file_exists($fileTmpPath) || !is_readable($fileTmpPath)) {
            return [
                'success' => false,
                'message' => 'CSV file could not be read.'
            ];
        }

        $handle = fopen($fileTmpPath, 'r');

        if ($handle === false) {
            return [
                'success' => false,
                'message' => 'Failed to open CSV file.'
            ];
        }

        $header = fgetcsv($handle);

        if (!$header) {
            fclose($handle);
            return [
                'success' => false,
                'message' => 'CSV file is empty.'
            ];
        }

        $header = array_map('trim', $header);
        $requiredColumns = ['degree_id', 'district', 'cutoff_mark', 'year'];

        foreach ($requiredColumns as $column) {
            if (!in_array($column, $header)) {
                fclose($handle);
                return [
                    'success' => false,
                    'message' => "Missing required column: {$column}"
                ];
            }
        }

        $headerMap = array_flip($header);

        while (($row = fgetcsv($handle)) !== false) {
            if (count(array_filter($row, function ($value) {
                return trim((string)$value) !== '';
            })) === 0) {
                continue;
            }

            $degree_id   = trim($row[$headerMap['degree_id']] ?? '');
            $district    = trim($row[$headerMap['district']] ?? '');
            $cutoff_mark = trim($row[$headerMap['cutoff_mark']] ?? '');
            $year        = trim($row[$headerMap['year']] ?? '');

            if ($degree_id === '' || $district === '' || $cutoff_mark === '' || $year === '') {
                $skipped++;
                continue;
            }

            if (!is_numeric($degree_id) || !is_numeric($cutoff_mark) || !is_numeric($year)) {
                $skipped++;
                continue;
            }

            $degree_id = (int)$degree_id;
            $district = trim($district);
            $cutoff_mark = (float)$cutoff_mark;
            $year = (int)$year;

            try {
                $existing = $this->getByDegreeDistrictYear($degree_id, $district, $year);

                if ($existing) {
                    $result = $this->updateCutoffMark($degree_id, $district, $year, $cutoff_mark);
                    if ($result) {
                        $updated++;
                    } else {
                        $skipped++;
                    }
                } else {
                    $result = $this->insertCutoff($degree_id, $district, $cutoff_mark, $year);
                    if ($result) {
                        $inserted++;
                    } else {
                        $skipped++;
                    }
                }
            } catch (Exception $e) {
                error_log('DegreeCutoff CSV import error: ' . $e->getMessage());
                $skipped++;
            }
        }

        fclose($handle);

        return [
            'success' => true,
            'inserted' => $inserted,
            'updated' => $updated,
            'skipped' => $skipped
        ];
    }

    /**
     * Find existing cutoff by degree, district, and year
     */
    public function getByDegreeDistrictYear($degree_id, $district, $year)
    {
        $sql = "SELECT * FROM degree_cutoffs 
                WHERE degree_id = :degree_id 
                AND district = :district 
                AND year = :year 
                LIMIT 1";

        return $this->fetch($sql, [
            'degree_id' => $degree_id,
            'district' => $district,
            'year' => $year
        ]);
    }

    /**
     * Insert new cutoff row
     */
    public function insertCutoff($degree_id, $district, $cutoff_mark, $year)
    {
        $sql = "INSERT INTO degree_cutoffs (degree_id, district, cutoff_mark, year)
                VALUES (:degree_id, :district, :cutoff_mark, :year)";

        $stmt = $this->query($sql, [
            'degree_id' => $degree_id,
            'district' => $district,
            'cutoff_mark' => $cutoff_mark,
            'year' => $year
        ]);

        return $stmt ? true : false;
    }

    /**
     * Update existing cutoff mark
     */
    public function updateCutoffMark($degree_id, $district, $year, $cutoff_mark)
    {
        $sql = "UPDATE degree_cutoffs
                SET cutoff_mark = :cutoff_mark
                WHERE degree_id = :degree_id
                AND district = :district
                AND year = :year";

        $stmt = $this->query($sql, [
            'cutoff_mark' => $cutoff_mark,
            'degree_id' => $degree_id,
            'district' => $district,
            'year' => $year
        ]);

        return $stmt ? true : false;
    }
}