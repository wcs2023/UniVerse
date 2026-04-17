<?php
class DegreeProgram extends Model
{
    public function getUniversityIdByName($universityName)
    {
        $query = "SELECT university_id 
                  FROM universities 
                  WHERE university_name = :university_name
                  LIMIT 1";

        $row = $this->fetch($query, [
            'university_name' => trim($universityName)
        ]);

        return $row['university_id'] ?? null;
    }

    public function findByUnicodeAndUniversity($unicode, $universityId)
    {
        $query = "SELECT * 
                  FROM degree_programs
                  WHERE unicode = :unicode AND university_id = :university_id
                  LIMIT 1";

        return $this->fetch($query, [
            'unicode' => trim($unicode),
            'university_id' => $universityId
        ]);
    }

    public function createDegreeProgram($data)
    {
        $query = "INSERT INTO degree_programs (
                    degree_name,
                    unicode,
                    university_id,
                    stream,
                    details
                  ) VALUES (
                    :degree_name,
                    :unicode,
                    :university_id,
                    :stream,
                    :details
                  )";

        return $this->query($query, [
            'degree_name' => $data['degree_name'],
            'unicode' => $data['unicode'],
            'university_id' => $data['university_id'],
            'stream' => $data['stream'],
            'details' => $data['details']
        ]);
    }

    public function updateDegreeProgram($id, $data)
    {
        $query = "UPDATE degree_programs SET
                    degree_name = :degree_name,
                    unicode = :unicode,
                    university_id = :university_id,
                    stream = :stream,
                    details = :details
                  WHERE degree_program_id = :degree_program_id";

        return $this->query($query, [
            'degree_program_id' => $id,
            'degree_name' => $data['degree_name'],
            'unicode' => $data['unicode'],
            'university_id' => $data['university_id'],
            'stream' => $data['stream'],
            'details' => $data['details']
        ]);
    }

    public function importFromCsv($filePath)
    {
        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'message' => 'Uploaded file not found.'
            ];
        }

        $handle = fopen($filePath, 'r');

        if (!$handle) {
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

        $requiredColumns = ['degree_name', 'unicode', 'university_name', 'stream', 'details'];

        foreach ($requiredColumns as $column) {
            if (!in_array($column, $header)) {
                fclose($handle);
                return [
                    'success' => false,
                    'message' => "Missing required column: {$column}"
                ];
            }
        }

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count(array_filter($row)) === 0) {
                continue;
            }

            $csvData = array_combine($header, $row);

            $degreeName = trim($csvData['degree_name'] ?? '');
            $unicode = trim($csvData['unicode'] ?? '');
            $universityName = trim($csvData['university_name'] ?? '');
            $stream = trim($csvData['stream'] ?? '');
            $details = trim($csvData['details'] ?? '');

            if ($degreeName === '' || $unicode === '' || $universityName === '' || $stream === '' || $details === '') {
                $skipped++;
                continue;
            }

            $universityId = $this->getUniversityIdByName($universityName);

            if (!$universityId) {
                $skipped++;
                continue;
            }

            $decodedJson = json_decode($details, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $skipped++;
                continue;
            }

            $normalizedData = [
                'degree_name' => $degreeName,
                'unicode' => $unicode,
                'university_id' => $universityId,
                'stream' => $stream,
                'details' => json_encode($decodedJson, JSON_UNESCAPED_UNICODE)
            ];

            $existing = $this->findByUnicodeAndUniversity($unicode, $universityId);

            if ($existing) {
                $ok = $this->updateDegreeProgram($existing['degree_program_id'], $normalizedData);
                if ($ok) {
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                $ok = $this->createDegreeProgram($normalizedData);
                if ($ok) {
                    $inserted++;
                } else {
                    $skipped++;
                }
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
}