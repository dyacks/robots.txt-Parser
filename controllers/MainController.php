<?php

namespace App\Controllers;

use App\Models\CheckedLinks;
use App\Classes\View;

class MainController {

    public function actionGetFromId(){
        $id = $_REQUEST['id'];
        // Active Record
        try {
            $checkedLink = new CheckedLinks();
            var_dump($checkedLink->getOneLinks($id));
        }catch(ModelException $e){
            $view = new View();
            $view->error = $e->getMessage();
            $view->display('error');
        }
    }

    public function actionGetAll(){
        // Active Record
        $checkedLink = new CheckedLinks();
        var_dump($checkedLink->getAllLinks());
    }

    public function actionAddOne(){
        $link = $_REQUEST['link'];
        // Active Record
        $checkedLink = new CheckedLinks();
        $checkedLink->link = $link;
        $checkedLink->datetime = date('Y-m-d H:i:s');
        echo $checkedLink->save();
        echo (", id = $checkedLink->id, link = $link");
    }

    public function actionUpdate(){
        $id = $_REQUEST['id'];
        $link = $_REQUEST['link'];
        try {
            $checkedLink = CheckedLinks::getOneLinks($id);
        }catch(ModelException $e){
            $view = new View();
            $view->err = $e->getMessage();
            $view->display('error');
        }
        $checkedLink->link = $link;
        $checkedLink->datetime = date('Y-m-d H:i:s');
        $checkedLink->save();
        echo ("ok, id = $id");
    }

    public function actionIndex()
    {
		/*
			if (isset($_GET['url'])) {
				$url       = strtolower($_GET['url']);
				$id        = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
			}
		*/
        // $id = $_GET['id'];
        /*
                $db = new DB;
                var_dump($db->query("SELECT * FROM checkedLinks"));
               // var_dump($db->getAllLinks("SELECT * FROM checkedLinks WHERE id=:id", [':id' => 1]));
        */
        //var_dump(CheckedLinksModel::getOneLinks(2));
        //var_dump(CheckedLinksModel::getAllLinks());

        // Active Record
     //   echo 'Action Index';
        $view = new View();
        $view->display('index');
    }




}