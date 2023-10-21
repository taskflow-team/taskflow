<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../model/Grupo.php");
require_once(__DIR__ . "/../dao/GrupoDAO.php");
require_once(__DIR__ . "/../service/GrupoService.php");
require_once(__DIR__ . "/Controller.php");

class GrupoController extends Controller
{
    private GrupoDAO $grupoDao;
    private GrupoService $grupoService;

    public function __construct()
    {
        $this->grupoDao = new GrupoDAO();
        $this->grupoService = new GrupoService();

        $this->setActionDefault("create");

        $this->handleAction();
    }

    protected function create()
    {
        $dados["id_grupo"] = 0;
        $this->loadView("pages/group/group.php", $dados, "", "");
    }

    protected function list()
    {
        // Verifica se o usuário está logado
        if (!$this->usuarioLogado()) {
            exit; // Se não estiver logado, encerra a execução
        }

        // Obtém o ID do usuário da sessão
        $userID = $_SESSION[SESSAO_USUARIO_ID];

        // Obtém todas as grupos
        $grupos = $this->grupoDao->getUserGrupos($userID);

        // Cria um array de resposta contendo a mensagem de sucesso e os dados das grupos
        $response = array(
            'message' => 'Success',
            'data' => array_map(function ($grupo) {
                // Converte o objeto grupo em um objeto anônimo contendo apenas as propriedades necessárias
                return (object) array(
                    'idtb_grupo' => $grupo->getIdtbGrupo(),
                    'codigo_convite' => $grupo->getCodigo_convite(),
                    'nome' => $grupo->getNome(),
                    'id_usuario' => $grupo->getId_usuario(),
                    'id_grupo' => $grupo->getId_grupo(),
                    'administrador' => $grupo->getAdministrador(),
                    'pontos' => $grupo->getPontos(),
                );
            }, $grupos)
        );

        // Limpa qualquer saída anterior antes de definir os cabeçalhos JSON
        ob_clean();

        // Define o cabeçalho para indicar que a resposta é um JSON
        header('Content-Type: application/json');

        // Imprime a resposta em formato JSON
        echo json_encode($response);
    }

    protected function rename()
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

        $groupId = $requestData['groupId'];
        $groupName = $requestData['groupName'];

        $grupo = $this->grupoDao->findByIdGrupo($groupId);

        $grupo->setNome($groupName);

        $erros = $this->grupoService->validarDados($grupo);
        if (empty($erros)) {
            try {
                $this->grupoDao->updateGrupo($grupo);

                $response = array(
                    'ok' => true,
                    'message' => 'Grupo atualizada com sucesso.'
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

    protected function join()
    {
        // Obter os dados JSON brutos do corpo da requisição
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true); // Converter JSON para um array associativo
    
        $groupCode = $requestData['groupCode'];
        $userID = $requestData['userID'];
        $isAdministrator = $requestData['isAdministrator'];
    
        try {
            // Check if the user is already a member of the group
            if ($this->grupoDao->isUserMemberOfGroup($groupCode, $userID)) {
                $response = array(
                    'ok' => false,
                    'message' => 'You are already a member of this group'
                );
    
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
    
            // Validate groupCode
            $group = $this->grupoDao->findGroupByCode($groupCode);
    
            if (!$group) {
                $response = array(
                    'ok' => false,
                    'message' => 'Group not found'
                );
    
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
    
            $result = $this->grupoDao->joinGroup($group->getId_grupo(), $userID, $isAdministrator);
    
            if ($result) {
                // Successfully joined the group
                $response = array(
                    'ok' => true,
                    'message' => 'Successfully joined the group'
                );
    
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } else {
                // Failed to join the group
                $response = array(
                    'ok' => false,
                    'message' => 'Failed to join the group'
                );
    
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } catch (PDOException $e) {
            // If an error occurs during the join process, send an error response as JSON
            $response = array(
                'ok' => false,
                'message' => 'An error occurred during the request',
                'error' => $e->getMessage() // Use the error message from the exception
            );
    
            // Send the response as JSON
            http_response_code(400);
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

        $groupId = $_GET['id'];

        // Deleta o grupo
        $this->grupoDao->deleteGrupo($groupId);

        // Envia a resposta como JSON
        $response = array(
            'message' => 'Grupo deletada com sucesso.',
        );

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    protected function leaveGroup()
    {
        if (!$this->usuarioLogado()) {
            exit;
        }

        $userId = $_SESSION[SESSAO_USUARIO_ID];
        $groupId = $_GET['id'];

        // Deleta o grupo
        $this->grupoDao->leaveGrupo($groupId, $userId);

        // Envia a resposta como JSON
        $response = array(
            'message' => 'Você saiu do grupo com sucesso.',
        );

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    protected function requestTest()
    {
        $response = array(
            'message' => 'Request received successfully',
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

#Criar objeto da classe
$grupoCont = new GrupoController();
