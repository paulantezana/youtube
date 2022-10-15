<?php
require_once(__DIR__ . '/../Models/Usuario.php');

class UsuarioController extends Controller
{
    private $usuarioModel;

    public function __construct(PDO $coneccion)
    {
        $this->usuarioModel = new Usuario($coneccion);
    }

    public function home(){
        $usuarios = $this->usuarioModel->getAll();

        echo '<pre>';
        var_dump($usuarios);
        echo '</pre>';
    }
}