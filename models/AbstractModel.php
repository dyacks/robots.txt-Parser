<?php

abstract class AbstractModel {
    
    protected static $table;
    protected $data = [];

    public function __set($key, $value){
        $this->data[$key] = $value;
    }

    public function __get($key){
        return $this->data[$key];
    }

    public function insert(){
        $cols = array_keys($this->data);
        $ins = []; // data
        // add : in the beginning
        foreach ($cols as $col){
            $ins[':' . $col] = $this->data[$col];
        }

        $sql = 'INSERT INTO ' . static::$table . '
            (' . implode(', ', $cols) . ')
        VALUES 
            (' . implode(', ', array_keys($ins)) . ')';

        $db = new DB();
        echo $db->execute($sql, $ins);
    }

    public static function getAllLinks(){
        $class = get_called_class();
        $sql = 'SELECT * FROM ' . static::$table;
        $db = new DB();
        $db->setClassName($class);
        return $db->query($sql);
    }

    public static function getOneLinks($id){
        $class = get_called_class();
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE id=:id';
        $db = new DB();
        $db->setClassName($class);
        return $db->query($sql, [':id' => $id])[0];
    }

}