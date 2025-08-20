<?php 

class Home extends Controller{

    public function index(){

        $model = new Model;
        $arr['id'] = 1;
        $arr['name'] = "Eathrone";
        $result  = $model->where($arr);

        show($result);

        $this->view('home');
    }
}
