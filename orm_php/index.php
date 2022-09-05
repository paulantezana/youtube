<?php
    require_once(__DIR__ . '/Database.php');
    require_once(__DIR__ . '/Orm.php');
    require_once(__DIR__ . '/Usuario.php');
    require_once(__DIR__ . '/Cliente.php');

    $database = new Database();
    $coneccion = $database->getConnection();

    $usuarioModel = new Usuario($coneccion);
    $usuarios = $usuarioModel->getAll();

    // echo '<pre>';
    // var_dump($usuarios);
    // echo '</pre>';

    $clienteModel = new Cliente($coneccion);
    $clientes = $clienteModel->getAll();

    // echo '<pre>';
    // var_dump($clientes);
    // echo '</pre>';

    $usuario = $usuarioModel->getById(3);
    $cliente = $clienteModel->getById(3);

    // // var_dump($cliente);
    // $usuarioModel->deleteById(1);

    // $clienteModel->deleteById(3);

    // $clienteModel->updateById(1,[
    //     'nombres' => 'ana',
    //     'apellidos' => 'quiñones',
    //     'direccion' => 'av. lima',
    // ]);

    // $clienteModel->insert([
    //     'nombres' => 'maria'
    // ]);

    // $usuarioModel->insert([
    //     'nombre_usuario' => 'juan'
    // ]);

    $data = $usuarioModel->paginate(1,2);

    echo '<pre>';
    var_dump($data);
    echo '</pre>';