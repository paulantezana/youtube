<?php
class PageController extends Controller
{
    public function home()
    {
        // echo 'estoy en home';
        // require_once(__DIR__ . '/../Views/home.view.php');
        $this->render('home');
    }
    public function listar()
    {
        $this->render('listar');
    }
    public function modificar()
    {
        $this->render('modificar');
    }

    public function nuevo()
    {
        $this->render('nuevo');
    }
    public function eliminar()
    {
        $this->render('eliminar');
    }
}
