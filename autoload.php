<?php

function __autoload($class){

        if (file_exists(__DIR__ . '/models/' . $class . '.php')) {
            require __DIR__ . '/models/' . $class . '.php';

        } elseif (file_exists(__DIR__ . '/views/' . $class . '.php')) {
            require __DIR__ . '/views/' . $class . '.php';

        } elseif (file_exists(__DIR__ . '/controllers/' . $class . '.php')) {
            require __DIR__ . '/controllers/' . $class . '.php';

        } elseif (file_exists(__DIR__ . '/classes/' . $class . '.php')) {
            require __DIR__ . '/classes/' . $class . '.php';
        } else {
           // die($class);
            $classParts = explode('\\', $class);
            $classParts[0] = __DIR__;
            $path = implode(DIRECTORY_SEPARATOR, $classParts) . '.php';
      //die($path);
            if(file_exists($path)){
                require $path;
            } else {
                throw new \RuntimeException("Class: " . $class . " - Not Found!");
            }
        }



}

