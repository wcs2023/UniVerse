<?php 

class Degrees extends Controller{

    private $degreeModel;
    public function __construct(){
        $this->degreeModel = $this->model("DegreeModel");
    }
    public function degree_suggestion_index(){
        $data = [];
        
        $this->view("actors/students/degree_suggestion",$data);
    }



    public function show_result(){
        // $data = [];
        
        // $this->view("actors/students/degree_result",$data);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            if(empty($_POST)){
                die("Invalid Request");
            }

            $zscore = trim($_POST['zscore'] ?? '');
            $stream = trim($_POST['stream'] ?? '');
            $district = trim($_POST['district'] ?? '');

            $errors = [];

            if($zscore === ''){
                $errors[] = "Z-score is required";
            }
            elseif(!is_numeric($zscore)){
                $errors[] = "Z-score must be a number";
            }
            elseif($zscore < 0 || $zscore > 4){
                $errors[] = "Z-score must be between 0  and 4";
            }

            if($stream === ''){
                $errors[] = "Stream is required";
            }
            if($district === ''){
                $errors[] = "District is required";
            }

            if(!empty($errors)){
                $data = [
                    'errors'=>$errors,
                    'old' =>[
                        'zscore' => $zscore,
                        'stream' => $stream,
                        'district' => $district
                    ]
                ];

                $this->view("actors/students/degree_suggestion",$data);
                return ;
            }

            $infos = $this->degreeModel->getDegreeRecommendations($zscore,$district,$stream);

            $data = [
                'zscore' =>$zscore,
                'district'=>$district,
                'stream'=>$stream,
                'infos'=>$infos
            ];

            $this->view('actors/students/degree_result',$data);



        }
        else{
            hea
        }
    }
}