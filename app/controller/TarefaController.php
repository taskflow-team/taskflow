<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../model/Tarefa.php");
require_once(__DIR__ . "/../dao/TarefaDAO.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../service/TarefaService.php");
require_once(__DIR__ . "/Controller.php");

#[AllowDynamicProperties]
class TarefaController extends Controller
{

    private TarefaDAO $tarefaDao;
    private TarefaService $tarefaService;

    public function __construct()
    {
        $this->tarefaDao = new TarefaDAO();
        $this->tarefaService = new TarefaService();
        $this->usuarioDao = new UsuarioDAO();

        $this->setActionDefault("list");

        $this->handleAction();
    }

    protected function create()
    {
        $dados["id"] = 0;
        $this->loadView("home/form.php", $dados, "", "");
    }

    protected function list()
    {
        // Verifica se o usuário está logado
        if (!$this->usuarioLogado()) {
            exit; // Se não estiver logado, encerra a execução
        }

        // Obtém o ID do usuário da sessão
        $userID = $_SESSION[SESSAO_USUARIO_ID];

        // Get user's request body
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true);

        $rule = $requestData['rule'];
        $listID = $requestData['listID'];
        $groupID = $requestData['groupID'];

        if ($rule === 'priority') {
            $query_rule = 'prioridade';
        } else if ($rule === 'difficulty') {
            $query_rule = 'dificuldade';
        } else {
            $query_rule = 'id_tarefa';
        }

        if(is_null($groupID))
        {
            $tarefas = $this->tarefaDao->listTarefas($userID, $listID, $query_rule);
        }
        else
        {
            $tarefas = $this->tarefaDao->listTarefasGrupo($groupID, $listID, $query_rule);
        }

        // Cria um array de resposta contendo a mensagem de sucesso e os dados das tarefas
        $response = array(
            'message' => 'Success',
            'rule' => $requestData,
            'data' => array_map(function ($tarefa) {
                // Converte o objeto tarefa em um objeto anônimo contendo apenas as propriedades necessárias
                return (object) array(
                    'id_tarefa' => $tarefa->getId_tarefa(),
                    'nome_tarefa' => $tarefa->getNome_tarefa(),
                    'descricao_tarefa' => $tarefa->getDescricao_tarefa(),
                    'dificuldade' => $tarefa->getDificuldade(),
                    'prioridade' => $tarefa->getPrioridade(),
                    'valor_pontos' => $tarefa->getValor_pontos(),
                    'data_criacao' => $tarefa->getData_criacao(),
                    'concluida' => $tarefa->getConcluida(),
                    'id_usuario' => $tarefa->getId_usuario(),
                    'idtb_listas' => $tarefa->getIdtb_listas(),
                    'id_grupo' => $tarefa->getId_grupo(),
                );
            }, $tarefas)
        );

        // Limpa qualquer saída anterior antes de definir os cabeçalhos JSON
        ob_clean();

        // Define o cabeçalho para indicar que a resposta é um JSON
        header('Content-Type: application/json');

        // Imprime a resposta em formato JSON
        echo json_encode($response);
    }

    protected function edit()
    {
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true);

        if ($requestData === null) {
            $response = array(
                'message' => 'Dados JSON inválidos',
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $formData = $requestData['formData'];
        $taskId = $requestData['taskId'];

        $tarefa = $this->tarefaDao->findByIdTarefa($taskId);

        $tarefa->setNome_tarefa($formData['nome']);
        $tarefa->setDescricao_tarefa($formData['descricao']);
        $tarefa->setDificuldade($formData['dificuldade']);
        $tarefa->setPrioridade($formData['prioridade']);
        $tarefa->setValor_pontos($formData['valor_pontos']);
        $tarefa->setIdtb_listas($formData['idtb_listas']);

        $erros = $this->tarefaService->validarDados($tarefa);
        if (empty($erros)) {
            try {
                $this->tarefaDao->updateTarefa($tarefa);

                $response = array(
                    'ok' => true,
                    'message' => 'Tarefa atualizada com sucesso.'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } catch (PDOException $e) {
                $response = array(
                    'ok' => false,
                    'message' => 'Ocorreu um erro durante a requisição',
                    'error' => $e->getMessage()
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            $response = array(
                'ok' => true,
                'message' => 'Ocorreram erros de validação',
                'errors' => $erros
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    protected function completeTask()
    {
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true);

        if ($requestData === null) {
            $response = array(
                'message' => 'Dados JSON inválidos',
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $tarefaId = $requestData['taskId'];
        $taskCompleted = $requestData['taskCompleted'];
        $userId = $requestData['userID'];

        $tarefa = $this->tarefaDao->findByIdTarefa($tarefaId);

        $tarefa->setConcluida($taskCompleted ? 1 : 0);

        try {
            $this->tarefaDao->updateTarefa($tarefa);

            // Se a tarefa foi marcada como concluída, adicionar os pontos ao usuário
            if ($taskCompleted) {
                $user = $this->usuarioDao->findById($userId);
                $userPoints = $user->getPontos();
                $taskPoints = $tarefa->getValor_pontos();
    
                // Adiciona os pontos da tarefa aos pontos do usuário
                $user->setPontos($userPoints + $taskPoints);
                $this->usuarioDao->update($user);
            } else if(!$taskCompleted)
            {
                $user = $this->usuarioDao->findById($userId);
                $userPoints = $user->getPontos();
                $taskPoints = $tarefa->getValor_pontos();
    
                // Adiciona os pontos da tarefa aos pontos do usuário
                $user->setPontos($userPoints - $taskPoints);
                $this->usuarioDao->update($user);
            }

            $response = array(
                'ok' => true,
                'message' => 'Tarefa atualizada com sucesso.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } catch (PDOException $e) {
            $response = array(
                'ok' => false,
                'message' => 'Ocorreu um erro durante a requisição',
                'error' => $e->getMessage()
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    protected function save()
    {
        // Obter os dados JSON brutos do corpo da requisição
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true); // Converter JSON para um array associativo

        // Verificar se a análise do JSON foi bem-sucedida
        if ($requestData === null) {
            $response = array(
                'message' => 'Dados JSON inválidos',
            );

            // Enviar a resposta como JSON
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Extrair os dados do array JSON
        $formData = $requestData['formData'];
        $userID = $requestData['userID'];
        $listID = $requestData['listID'];
        $groupID = $requestData['groupID'];

        // Supondo que a classe Tarefa tenha os métodos setters apropriados para as propriedades
        $tarefa = new Tarefa();
        $tarefa->setNome_tarefa($formData['nome']);
        $tarefa->setDescricao_tarefa($formData['descricao']);
        $tarefa->setDificuldade($formData['dificuldade']);
        $tarefa->setPrioridade($formData['prioridade']);
        $tarefa->setValor_pontos($formData['valor_pontos']);
        $tarefa->setData_criacao(date('Y-m-d H:i:s'));
        $tarefa->setId_usuario($userID);
        $tarefa->setIdtb_listas($listID);
        $tarefa->setId_grupo($groupID);

        // Validar os dados da tarefa
        $erros = $this->tarefaService->validarDados($tarefa);
        if (empty($erros)) {
            try {
                // Inserir a tarefa no banco de dados
                $this->tarefaDao->insertTarefa($tarefa);

                // Enviar mensagem de sucesso como JSON
                $response = array(
                    'ok' => true,
                    'message' => 'Tarefa salva com sucesso.'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } catch (PDOException $e) {
                // Se ocorrer um erro durante a inserção, enviar mensagem de erro como JSON
                $response = array(
                    'ok' => false,
                    'message' => 'Ocorreu um erro durante a requisição',
                    'error' => $e->getMessage() // Usar a mensagem de erro da exceção
                );

                // Enviar a resposta como JSON
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            // Se houver erros de validação, enviar a resposta com os erros como JSON
            $response = array(
                'ok' => true,
                'message' => 'Ocorreram erros de validação',
                'errors' => $erros // Incluir os erros de validação na resposta
            );

            // Enviar a resposta como JSON
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    protected function delete()
    {
        if (!$this->usuarioLogado()) {
            exit;
        }

        $tarefaId = $_GET['taskId'];
        $taskCompleted = $_GET['taskCompleted'] == 'true' ? 1 : 0;
        $userId = $_GET['userId'];

        $tarefa = $this->tarefaDao->findByIdTarefa($tarefaId);

        if ($taskCompleted) {
            $user = $this->usuarioDao->findById($userId);
            $userPoints = $user->getPontos();
            $taskPoints = $tarefa->getValor_pontos();

            // Adiciona os pontos da tarefa aos pontos do usuário
            $user->setPontos($userPoints - $taskPoints);
            $this->usuarioDao->update($user);
        } 

        // Deleta a tarefa
        $this->tarefaDao->deleteTarefa($tarefaId);

        // Envia a resposta como JSON
        $response = array(
            'message' => 'Tarefa deletada com sucesso.',
        );

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    protected function requestTest()
    {
        $response = array(
            'message' => 'Request recieved successfully',
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

#Criar objeto da classe
$tarefaCont = new TarefaController();
