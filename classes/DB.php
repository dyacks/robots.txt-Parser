<?php

class DB {

    private $dbh;
    private $className = 'stdClass';

    public function __construct() {

        $this->dbh = new PDO('mysql:dbname=robots;host=localhost', 'root', '');
    }

    public function setClassName($className){
        $this->className = $className;
    }

    public function query($sql, $params=[]){
        $sth = $this->dbh->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll(PDO::FETCH_CLASS, $this->className);
    }

    /**
     * @param $sql
     * @param array $params
     * @return bool
     */
    public function execute($sql, $params=[]){
        $sth = $this->dbh->prepare($sql);
        return $sth->execute($params);
    }

}