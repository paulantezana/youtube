<?php
    class Controller{
        protected function render($path ,$parameters = [], $layout = ''){
            require_once(__DIR__ . '/../Views/'.$path.'.view.php');
        }
    }