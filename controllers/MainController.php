<?php

class MainController {


    
    public function actionAddOne(){
        // Active Record
        $checkedLink = new CheckedLinksModel();
        $checkedLink->link = "http://www.i.ua";
        $checkedLink->datetime = date('Y-m-d H:i:s');
        $checkedLink->insert();
        echo ($checkedLink->id);
    }

    public function actionGetAll(){
        // Active Record
        $checkedLink = new CheckedLinksModel();
        var_dump($checkedLink->getAllLinks());
    }

    public function actionGetFromId($id=1){
        // Active Record
        $checkedLink = new CheckedLinksModel();
        var_dump($checkedLink->getOneLinks($id));
    }

    public function actionUpdate(){
        $checkedLink = CheckedLinksModel::getOneLinks(2);
        $checkedLink->link = 'www.meta.ua';
        $checkedLink->datetime = date('Y-m-d H:i:s');
        $checkedLink->update();
    }

    public function actionIndex()
    {
        // $id = $_GET['id'];
        /*
                $db = new DB;
                var_dump($db->query("SELECT * FROM checkedLinks"));
               // var_dump($db->getAllLinks("SELECT * FROM checkedLinks WHERE id=:id", [':id' => 1]));
        */
        //var_dump(CheckedLinksModel::getOneLinks(2));
        //var_dump(CheckedLinksModel::getAllLinks());

        // Active Record
        echo 'Action Index';
    }




}