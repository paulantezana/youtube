<?php
    class Cliente extends Orm {
        public function __construct(PDO $coneccion)
        {
            parent::__construct('id','clientes', $coneccion);
        }
    }