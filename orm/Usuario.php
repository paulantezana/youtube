<?php

require_once(__DIR__ . '/Orm.php');

class Usuario extends Orm
{
    public function __construct(PDO $connection)
    {
        parent::__construct('usuarios', 'id', $connection);
    }
}