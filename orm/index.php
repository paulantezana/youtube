<?php
require_once(__DIR__ . '/Database.php');
require_once(__DIR__ . '/Cliente.php');
require_once(__DIR__ . '/Usuario.php');

$database = new Database();
$coneccion = $database->getConnection();

$usuarioModel = new Usuario($coneccion);
$clienteModel = new Cliente($coneccion);

$usuarios = $usuarioModel->getAll();
$clientes = $clienteModel->getAll();

echo '<pre>';
var_dump($usuarios);
echo '</pre>';

echo '<pre>';
var_dump($clientes);
echo '</pre>';
