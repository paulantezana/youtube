<?php
    class Usuario extends Orm {
        public function __construct(PDO $connecion)
        {
            parent::__construct('id','usuarios',$connecion);
        }
    }