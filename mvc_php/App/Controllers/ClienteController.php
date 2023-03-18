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
        $this->render('cliente', [], 'site');
    }
    public function new(){
        $this->render('clienteNew', [], 'site');
    }
    public function table(){
        $res = new Result();

        $clientes = $this->clienteModel->getAll();

        $res->success = true;
        $res->result = $clientes;

        echo json_encode($res);
    }
    public function edit(){
        $id = $_GET['id'];

        $cliente = $this->clienteModel->getById($id);
        
        $this->render('clienteNew', [
            'cliente' => $cliente,
        ], 'site');
    }
    public function create(){
        $res = new Result();

        $postData = file_get_contents('php://input');
        $body = json_decode($postData, true);

        $this->clienteModel->insert([
            'nombres' => $body['nombres'],
            'apellidos' => $body['apellidos'],
            'direccion' => $body['direccion'],
        ]);

        $res->success = true;
        $res->message = "El registro fue insertado correctamente";

        echo json_encode($res);
    }
    public function update(){
        $res = new Result();

        $postData = file_get_contents('php://input');
        $body = json_decode($postData, true);

        $this->clienteModel->updateById($body['id'], [
            'nombres' => $body['nombres'],
            'apellidos' => $body['apellidos'],
            'direccion' => $body['direccion'],
        ]);

        $res->success = true;
        $res->message = "El registro fue actualizado correctamente";

        echo json_encode($res);
    }
    public function delete(){
        $res = new Result();

        $postData = file_get_contents('php://input');
        $body = json_decode($postData, true);

        $this->clienteModel->deleteById($body['id']);

        $res->success = true;
        $res->message = "El registro fue eliminado correctamente";

        echo json_encode($res);
    }
    public function validateInput(){
        
    }
}