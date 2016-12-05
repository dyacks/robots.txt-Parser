<?php

class View {
    
    public function __construct() {

    }

    /**
     * OutputBuffer
     *
     * @param $template
     * @return string
     */
    public function render($template){
        ob_start();
        require __DIR__ . '/../views/' . $template . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function display($template){
        echo $this->render($template);
    }
}