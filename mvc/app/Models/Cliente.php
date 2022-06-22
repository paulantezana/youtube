<?php

require_once(__DIR__ . '/Orm.php');

class Cliente extends Orm
{
    public function __construct(PDO $connection)
    {
        parent::__construct('clientes', 'id', $connection);
    }
}