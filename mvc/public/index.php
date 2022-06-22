<?php
require_once(__DIR__ . '/../app/autoload.php');

session_start();

$router = new Router();
$router->run();