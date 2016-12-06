<?php

class MainController {
    
    public function actionIndex(){
       // $id = $_GET['id'];
        //$view = new View();
        //$view->display('index');
       // die();

        //$c = new CheckedLinksModel();
       // echo $c->getTable();
/*
        $db = new DB;
        var_dump($db->query("SELECT * FROM checkedLinks"));
       // var_dump($db->getAllLinks("SELECT * FROM checkedLinks WHERE id=:id", [':id' => 1]));
*/
        var_dump(CheckedLinksModel::getOneLinks(2));
    }
}