<?php 
#Classe controller para a Home do sistema
require_once(__DIR__ . "/Controller.php");

class HomeController extends Controller {

    public function __construct() {
        if(! $this->usuarioLogado())
            exit;

        $this->handleAction();
    }

    protected function home() {
        $this->loadView("home/index.php", []);
    }
}


#Criar objeto da classe
$homeCont = new HomeController();
