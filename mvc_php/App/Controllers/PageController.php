<?php
class PageController extends Controller
{
    public function home()
    {
        // echo 'estoy en home';
        // require_once(__DIR__ . '/../Views/home.view.php');
        $this->render('home', [], 'site');
    }
    public function listar()
    {
        $this->render('listar', [], 'site');
    }
    public function modificar()
    {
        $this->render('modificar', [], 'site');
    }

    public function nuevo()
    {
        $this->render('nuevo', [], 'site');
    }
    public function eliminar()
    {
        $this->render('eliminar', [], 'site');
    }
}
