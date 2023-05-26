<?php
#Classe controller para Usuário
require_once(__DIR__ . "/Controller.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../service/UsuarioService.php");
require_once(__DIR__ . "/../model/Usuario.php");


class UsuarioController extends Controller {

    private UsuarioDAO $usuarioDao;
    private UsuarioService $usuarioService;

    public function __construct() {
        $this->usuarioDao = new UsuarioDAO();
        $this->usuarioService = new UsuarioService();

        $this->setActionDefault("edit");

        $this->handleAction();
    }

    protected function create() {
        $dados["id"] = 0;
        $this->loadView("usuario/form.php", $dados);
    }

    protected function edit() {
        if(! $this->usuarioLogado())
        exit;

        $usuario = $this->findUsuarioById();
        $dados["usuario"] = $usuario;
        $this->loadView("/include/profile/profile.php", $dados);

    }

    protected function save() {
        //Captura os dados do formulário
        $dados["id"] = isset($_POST['id']) ? $_POST['id'] : 0;
        $nome = isset($_POST['nome']) ? trim($_POST['nome']) : NULL;
        $login = isset($_POST['login']) ? trim($_POST['login']) : NULL;
        $senha = isset($_POST['senha']) ? trim($_POST['senha']) : NULL;
        $confSenha = isset($_POST['conf_senha']) ? trim($_POST['conf_senha']) : NULL;
        $email = isset($_POST['email']) ? trim($_POST['email']) : NULL;
        $pontos = isset($_POST['pontos']) ? trim($_POST['pontos']) : NULL;
        $nivel = isset($_POST['nivel']) ? trim($_POST['nivel']) : NULL;
        $tarefas_concluidas = isset($_POST['tarefas_concluidas']) ? trim($_POST['tarefas_concluidas']) : NULL;


        //Cria objeto Usuario
        $usuario = new Usuario();
        $usuario->setNome($nome);
        $usuario->setLogin($login);
        $usuario->setSenha($senha);
        $usuario->setEmail($email);
        $usuario->setPontos($pontos);
        $usuario->setNivel($nivel);
        $usuario->setTarefas_concluidas($tarefas_concluidas);

        //Validar os dados
        $erros = $this->usuarioService->validarDados($usuario, $confSenha);
        if(empty($erros)) {
            //Persiste o objeto
            try {
                
                if($dados["id"] == 0)  //Inserindo
                    $this->usuarioDao->insert($usuario);
                else { //Alterando
                    $usuario->setId($dados["id"]);
                    $this->usuarioDao->update($usuario);
                }

                //TODO - Enviar mensagem de sucesso
                $msg = "Usuário salvo com sucesso.";
                $this->loadView("login/login.php", []);
                exit;
            } catch (PDOException $e) {
                $erros = array("An error occurred while saving the user on our database."); //erro deve ser um array para ser validado no método implode()               
            }
        }

        //Se há erros, volta para o formulário
        
        //Carregar os valores recebidos por POST de volta para o formulário
        $dados["usuario"] = $usuario;
        $dados["confSenha"] = $confSenha;

        $msgsErro = implode("<br>", $erros);
        $this->loadView("usuario/form.php", $dados, $msgsErro);
    }

    protected function delete() {
        $usuario = $this->findUsuarioById();
        if($usuario) {
            $this->usuarioDao->deleteById($usuario->getId());
        }
    }

    private function findUsuarioById() {
        $id = 0;
        if(isset($_GET['id']))
            $id = $_GET['id'];

        $usuario = $this->usuarioDao->findById($id);
        return $usuario;
    }


        /**
         * Set the value of dados
         *
         * @return  self
         */ 
        public function setDados($dados)
        {
                $this->$dados = $dados;

                return $this;
        }

        /**
         * Set the value of usuario
         *
         * @return  self
         */ 
        public function setUsuario($usuario)
        {
                $this->$usuario = $usuario;

                return $this;
        }
}


#Criar objeto da classe
$usuCont = new UsuarioController();
