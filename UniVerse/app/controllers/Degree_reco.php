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

        $errors =[];
        if(!is_numeric($zscore)){
            $errors[] = "Please enter a valid z-score.";
        }
        if(!$stream){
            $errors[] = "Please select a stream.";
        }
        if(!$district){
            $errors[] = "Please select a district.";
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
        $results = $degreeModel->searchByFilters((float)$zscore,$stream,$district);

        $data = [
            'title' =>'Recommendations',
            'zscore' => $zscore,
            'stream' => $stream,
            'district' => $district,
            'rows' => $results
        ];
        $this->view('actors/students/degree_results',$data);

    }


}
