<?php

class MainController {
    
    public function actionIndex(){
       // $id = $_GET['id'];
        $view = new View();
        $view->display('index');
       // die();
    }
}