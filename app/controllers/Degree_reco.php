<?php
class Degree extends Controller{

    public function index(){
        $data['title'] = "Degree Recommendation";
        $this->view('actors/students/degree_suggestion',$data);
    }

    public function result(){
        $zscore = isset($_POST['zscore']) ? trim($_POST['zscore']) : null;
        $stream = isset($_POST['stream']) ? trim($_POST['stream']) : null;
        $district = isset($_POST['district']) ? trim(strtolower($_POST['district'])) : null;

        $errors = [];

        if ($zscore === null || !is_numeric($zscore)) {
            $errors[] = "Please enter a valid z-score.";
        }

        $validStreams = ['maths', 'bio', 'arts', 'commerce', 'tech'];
        if ($stream === null || !in_array(strtolower($stream), $validStreams, true)) {
            $errors[] = "Please select a valid stream.";
        }

        if ($district === null || $district === '') {
            $errors[] = "Please select your district.";
        }


        if(!empty($errors)){
            $data = [
                'title' => "Degree Recommendation",
                'errors' => $errors,
                'old' => [
                    'zscore' => $zscore,
                    'stream' => $stream,
                    'district' => $district
                ]
            ];
            $this->view('actors/students/degree_suggestion',$data);
        }
        $degreeModel = new DegreeModel();
        // $rows = $degreeModel->searchByFilters((float)$zscore,$stream,$district);

        $data = [
            'title' =>'Recommendations',
            'zscore' => $zscore,
            'stream' => $stream,
            'district' => $district,
            // 'rows' => $rows
        ];
        $this->view('actors/students/degree_results',$data);

    }

    public function details($id = null)
    {
        header('Content-Type: application/json; charset=UTF-8');

        // Basic validation
        if ($id === null || !ctype_digit((string)$id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid id']);
            return;
        }

        $model = new DegreeModel();
        // $row   = $model->findById((int)$id);

        // if (!$row) {
        //     http_response_code(404);
        //     echo json_encode(['error' => 'Not found']);
        //     return;
        // }

        // Try to decode details as JSON; if it fails, treat as plain text
        $extra = null;
        $detailsStr = $row->details ?? '';
        $decoded = null;

        if (is_string($detailsStr) && $detailsStr !== '') {
            $decoded = json_decode($detailsStr, true);
        }

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $extra = $decoded; // structured details from DB
        }

        echo json_encode([
            // 'id'         => (int)$row->id,
            'unicode'    => $row->unicode ?? null,
            'university' => $row->university ?? null,
            'course'     => $row->course_name ?? null,
            'cutoff'     => $row->cutoff_mark ?? null,
            // If structured JSON exists, send it; else send plain text under 'details'
            'details'    => $extra ?: (string)$detailsStr,
            'structured' => $extra ? true : false,
        ]);
    }


}
