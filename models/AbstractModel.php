<?php

/**
 * Class AbstractModel
 *
 * @property $id
 * @property $link
 * @property $datetime
 */
abstract class AbstractModel {
    
    protected static $table;
    protected $data = [];

    public function __set($key, $value){
        $this->data[$key] = $value;
    }

    public function __get($key){
        return $this->data[$key];
    }

    public function __isset($key){
        return isset($this->data[$key]);
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
        $db->execute($sql, $ins);
        $this->id = $db->lastInsertId();
    }

    public static function getAllLinks(){
        $sql = 'SELECT * FROM ' . static::$table;
        $db = new DB();
        $db->setClassName(get_called_class());
        return $db->query($sql);
    }

    public static function getOneLinks($id){
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE id=:id';
        $db = new DB();
        $db->setClassName(get_called_class());
        return $db->query($sql, [':id' => $id])[0];
    }

    public function update(){
        $cols = [];
        $updateData = [];
        foreach ($this->data as $key => $value){
            $updateData[':' . $key] = $value;
            if($key == 'id') {
                continue;
            }
            $cols[] = $key . '=:' . $key;
        }
        //var_dump($updateData);
//echo
        $sql = '
            UPDATE ' . static::$table . '
            SET ' . implode(', ', $cols) . '
            WHERE id=:id
        '; //die;
        $db = new DB();
        $db->execute($sql, $updateData);
    }

}