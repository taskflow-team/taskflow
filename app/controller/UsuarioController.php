<?php
#Classe controller para Usuário
require_once(__DIR__ . "/Controller.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../service/UsuarioService.php");
require_once(__DIR__ . "/../model/Usuario.php");


class UsuarioController extends Controller
{

    private UsuarioDAO $usuarioDao;
    private UsuarioService $usuarioService;

    public function __construct()
    {
        $this->usuarioDao = new UsuarioDAO();
        $this->usuarioService = new UsuarioService();

        $this->setActionDefault("showProfile");

        $this->handleAction();
    }

    protected function uploadProfileImage()
    {
        if (!$this->usuarioLogado()) {
            exit;
        }

        $validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['profileImage']['type'];
    
        if (isset($_FILES['profileImage']) && in_array($fileType, $validImageTypes)) {
            $userId = $_POST['userId'];
            $fileExtension = strtolower(pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION));
            $fileName = "profile_" . $userId . "." . $fileExtension;
            $filePath = __DIR__ . "/../view/assets/img/" . $fileName;
    
            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $filePath)) {
                $this->usuarioDao->updateProfileImage($userId, $fileName);

                $response = array(
                    'ok' => true,
                    'imageUrl' => $filePath
                );
            } else {
                $response = array(
                    'ok' => false,
                    'message' => 'Failed to upload image.'
                );
            }
        } else {
            $response = array(
                'ok' => false,
                'message' => 'Invalid file or file type.'
            );
        }

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    protected function create()
    {
        $dados["id"] = 0;
        $this->loadView("pages/register/form.php", $dados);
    }

    protected function showProfile()
    {
        if (!$this->usuarioLogado()) {
            exit;
        }

        $this->loadView("/pages/userProfile/profile.php", []);
    }

    protected function getUserData()
    {
        if (!$this->usuarioLogado()) {
            exit;
        }
    
        $usuario = $this->findUsuarioById();
    
        if ($usuario) {
            // Convert the Usuario object to an associative array
            $userData = [
                'id' => $usuario->getId(),
                'nome' => $usuario->getNome(),
                'login' => $usuario->getLogin(),
                'senha' => $usuario->getSenha(),
                'email' => $usuario->getEmail(),
                'pontos' => $usuario->getPontos(),
                'nivel' => $usuario->getNivel(),
                'tarefas_concluidas' => $usuario->getTarefas_concluidas(),
                'foto_perfil' => $usuario->getFotoPerfil() 
            ];
    
            $response = [
                'ok' => true,
                'message' => 'Success',
                'user' => $userData
            ];
        } else {
            $response = [
                'ok' => false,
                'message' => 'User not found'
            ];
        }
    
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
    }    

    protected function edit()
    {
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true);

        // Check if the JSON data was successfully decoded
        if ($requestData === null) {
            $response = array(
                'ok' => false,
                'message' => 'Invalid JSON data',
            );

            ob_clean();
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $formData = $requestData['formData'];

        $usuario = $this->usuarioDao->findById($formData['id']);
        
        $usuario->setSenha($formData['senha']);
        $usuario->setEmail($formData['email']);

        $this->usuarioDao->update($usuario);

        $response = array(
            'ok' => true,
            'message' => 'Success',
        );

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    protected function save()
    {
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
        if (empty($erros)) {
            //Persiste o objeto
            try {

                if ($dados["id"] == 0)  //Inserindo
                    $this->usuarioDao->insert($usuario);
                else { //Alterando
                    $usuario->setId($dados["id"]);
                    $this->usuarioDao->update($usuario);
                }

                //TODO - Enviar mensagem de sucesso
                $msg = "Usuário salvo com sucesso.";
                $this->loadView("/pages/login/login.php", [], "", $msg);
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
        $this->loadView("pages/register/form.php", $dados, $msgsErro);
    }

    protected function delete()
    {
        $usuario = $this->findUsuarioById();
        if ($usuario) {
            $this->usuarioDao->deleteById($usuario->getId());
        }
    }

    private function findUsuarioById()
    {
        $id =  0;

        if (isset($_SESSION[SESSAO_USUARIO_ID])) {
            $id = $_SESSION[SESSAO_USUARIO_ID];
        }

        $usuario = $this->usuarioDao->findById($id);
        return $usuario;
    }
}


#Criar objeto da classe
$usuCont = new UsuarioController();
