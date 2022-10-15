<?php
require_once(__DIR__ . '/../Models/Cliente.php');

class ClienteController extends Controller
{
    private $clienteModel;

    public function __construct(PDO $coneccion)
    {
        $this->clienteModel = new Cliente($coneccion);
    }

    public function home(){
        $clientes = $this->clienteModel->paginate(1,3);

        echo '<pre>';
        var_dump($clientes);
        echo '</pre>';
    }
}