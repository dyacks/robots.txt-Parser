<?php

abstract class AbstractModel {
    
    protected static $table;

    public static function getAllLinks(){
        $sql = 'SELECT * FROM ' . static::$table;
        $db = new DB();
        return $db->query($sql);
    }

    public static function getOneLinks($id){
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE id=:id';
        $db = new DB();
        return $db->query($sql, [':id' => $id]);
    }

}