<?php

class DB {

    public function __construct() {
        $mysqli = new mysqli("localhost", "root", "", "robots");
        if ($mysqli->connect_errno) {
            echo "No DB connect: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $res = mysqli_query($mysqli, "SELECT * FROM checkedLinks");
        $row = mysqli_fetch_assoc($res);
        var_dump($row['link']);
    }
}