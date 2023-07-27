<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../model/Tarefa.php");
require_once(__DIR__ . "/../dao/TarefaDAO.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../service/TarefaService.php");
require_once(__DIR__ . "/Controller.php");

class TarefaController extends Controller {

    private TarefaDAO $tarefaDao;
    private TarefaService $tarefaService;

    public function __construct() {
        $this->tarefaDao = new TarefaDAO();
        $this->tarefaService = new TarefaService();

        $this->setActionDefault("list");

        $this->handleAction();
    }

    protected function list() {

        $this->loadView("home/index.php", []);
    }

    protected function create() {
        $dados["id"] = 0;
        $this->loadView("home/form.php", $dados, "", "");
    }


    protected function edit() {
        $tarefaId = isset($_POST['id']) ? $_POST['id'] : NULL;
        $nome = isset($_POST['nome']) ? trim($_POST['nome']) : NULL;
        $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : NULL;
        $dificuldade = isset($_POST['dificuldade']) ? trim($_POST['dificuldade']) : NULL;
        $prioridade = isset($_POST['prioridade']) ? trim($_POST['prioridade']) : NULL;
        $valor_pontos = isset($_POST['valor_pontos']) ? trim($_POST['valor_pontos']) : NULL;
        $concluida = isset($_POST['concluida']) ? trim($_POST['concluida']) : NULL;

        $tarefa = new Tarefa();
        $tarefa->setId_tarefa($tarefaId);
        $tarefa->setNome_tarefa($nome);
        $tarefa->setDescricao_tarefa($descricao);
        $tarefa->setDificuldade($dificuldade);
        $tarefa->setPrioridade($prioridade);
        $tarefa->setValor_pontos($valor_pontos);
        $tarefa->setConcluida($concluida);

        $this->tarefaDao->updateTarefa($tarefa);
        $msg = "Tarefa atualizada com sucesso.";
        $this->loadView("/home/form.php", [], "", $msg);
    }


    function testBaseUrl() {
        $baseUrl = "http://localhost/taskflow";

        $url = $baseUrl . "/controller/TarefaController.php?action=save";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }

   protected function test(){
        $this->testBaseUrl();
   }


    protected function save() {
        // Get the raw JSON data from the request body
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true); // Convert JSON to associative array

        $formData = json_decode($requestData['formData'], true);
        $userID = $requestData['userID'];

        // Assuming the Tarefa class has the appropriate setter methods for the properties
        $tarefa = new Tarefa();
        $tarefa->setNome_tarefa($formData['nome']);
        $tarefa->setDescricao_tarefa($formData['descricao']);
        $tarefa->setDificuldade($formData['dificuldade']);
        $tarefa->setPrioridade($formData['prioridade']);
        $tarefa->setValor_pontos($formData['valor_pontos']);
        $tarefa->setId_usuario($userID);

        print_r($tarefa);

        // Validar os dados
        $erros = $this->tarefaService->validarDados($tarefa);
        if (empty($erros)) {
            try {
                $this->tarefaDao->insertTarefa($tarefa);

                // TODO - Enviar mensagem de sucesso
                $msg = "Tarefa salva com sucesso.";
                $this->loadView("home/index.php", [], "", $msg);
                exit;
            } catch (PDOException $e) {
                $response = array(
                    'message' => 'Request recieved successfully',
                    'error' => $e
                );

                // Output the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }

        // // $msgsErro = implode("<br>", $erros);
        // // $this->loadView("/home/index.php", [], $msgsErro);
        // header('location:HomeController.php');
    }

    protected function delete() {
        if (!$this->usuarioLogado()) {
            exit;
        }

        $tarefaId = $_GET['id'];

        // Deleta a tarefa
        $this->tarefaDao->deleteTarefa($tarefaId);
        $this->loadView("/home/index.php", []);
        exit;
    }

    protected function requestTest() {
        $response = array(
            'message' => 'Request recieved successfully',
        );

        // Output the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

#Criar objeto da classe
$tarefaCont = new TarefaController();
