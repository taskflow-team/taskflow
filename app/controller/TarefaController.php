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
        // Captura os dados do formulário
        $dados["id"] = isset($_POST['id']) ? $_POST['id'] : 0;
        $nome = isset($_POST['nome']) ? trim($_POST['nome']) : NULL;
        $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : NULL;
        $dificuldade = isset($_POST['dificuldade']) ? trim($_POST['dificuldade']) : NULL;
        $prioridade = isset($_POST['prioridade']) ? trim($_POST['prioridade']) : NULL;
        $valor_pontos = isset($_POST['valor_pontos']) ? trim($_POST['valor_pontos']) : NULL;
        $concluida = isset($_POST['concluida']) ? trim($_POST['concluida']) : NULL;
    
        // Cria objeto Tarefa
        $tarefa = new Tarefa();
        $tarefa->setNome_tarefa($nome);
        $tarefa->setDescricao_tarefa($descricao);
        $tarefa->setDificuldade($dificuldade);
        $tarefa->setPrioridade($prioridade);
        $tarefa->setValor_pontos($valor_pontos);
        $tarefa->setConcluida($concluida);

        // Validar os dados
        $erros = $this->tarefaService->validarDados($tarefa);
        if (empty($erros)) {
            try {
                if ($dados["id"] == 0) {
                    // Inserindo
                    $this->tarefaDao->insertTarefa($tarefa);
                } else {
                    // Alterando
                    $tarefa->setId_tarefa($dados["id"]);
                    $this->tarefaDao->updateTarefa($tarefa);
                }
    
                // TODO - Enviar mensagem de sucesso
                $msg = "Tarefa salva com sucesso.";
                $this->loadView("home/index.php", [], "", $msg);
                exit;
            } catch (PDOException $e) {
                $erros = array("Ocorreu um erro ao salvar a tarefa no banco de dados.");
            }
        }
    
        $msgsErro = implode("<br>", $erros);
        $this->loadView("/home/index.php", [], $msgsErro); 
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
}

#Criar objeto da classe
$tarefaCont = new TarefaController();
