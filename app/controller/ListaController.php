<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../model/Lista.php");
require_once(__DIR__ . "/../dao/ListaDAO.php");
require_once(__DIR__ . "/../service/ListaService.php");
require_once(__DIR__ . "/Controller.php");

class ListaController extends Controller
{
    private ListaDAO $listaDao;
    private ListaService $listaService;

    public function __construct()
    {
        $this->listaDao = new ListaDAO();
        $this->listaService = new ListaService();

        $this->setActionDefault("list");

        $this->handleAction();
    }

    protected function create()
    {
        $dados["id_lista"] = 0;
        $this->loadView("home/form.php", $dados, "", "");
    }

    protected function listGroup()
    {
        // Verifica se o usuário está logado
        if (!$this->usuarioLogado()) {
            exit; // Se não estiver logado, encerra a execução
        }

        // Obtém o ID do usuário da sessão
        $userID = $_SESSION[SESSAO_USUARIO_ID];
        $group_id = $_GET['groupId'];

        // Obtém todas as listas
        $listas = $this->listaDao->getGroupLists($group_id);

        // Cria um array de resposta contendo a mensagem de sucesso e os dados das listas
        $response = array(
            'message' => 'Success',
            'data' => array_map(function ($lista) {
                // Converte o objeto lista em um objeto anônimo contendo apenas as propriedades necessárias
                return (object) array(
                    'id_lista' => $lista->getId_lista(),
                    'nome_lista' => $lista->getNome_lista(),
                );
            }, $listas)
        );

        // Limpa qualquer saída anterior antes de definir os cabeçalhos JSON
        ob_clean();

        // Define o cabeçalho para indicar que a resposta é um JSON
        header('Content-Type: application/json');

        // Imprime a resposta em formato JSON
        echo json_encode($response);
    }

    protected function list()
    {
        // Verifica se o usuário está logado
        if (!$this->usuarioLogado()) {
            exit; // Se não estiver logado, encerra a execução
        }

        // Obtém o ID do usuário da sessão
        $userID = $_SESSION[SESSAO_USUARIO_ID];

        // Obtém todas as listas
        $listas = $this->listaDao->getUserLists($userID);

        // Cria um array de resposta contendo a mensagem de sucesso e os dados das listas
        $response = array(
            'message' => 'Success',
            'data' => array_map(function ($lista) {
                // Converte o objeto lista em um objeto anônimo contendo apenas as propriedades necessárias
                return (object) array(
                    'id_lista' => $lista->getId_lista(),
                    'nome_lista' => $lista->getNome_lista(),
                );
            }, $listas)
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

        $id_lista = $requestData['listId'];
        $new_name = $requestData['listName'];

        $lista = $this->listaDao->findByIdLista($id_lista);

        $lista->setNome_lista($new_name);

        $erros = $this->listaService->validarDados($lista);
        if (empty($erros)) {
            try {
                $this->listaDao->updateLista($lista);

                $response = array(
                    'ok' => true,
                    'message' => 'Lista atualizada com sucesso.'
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
        $listName = $requestData['listName'];
        $userID = $requestData['userID'];
        $groupID = isset($requestData['groupID']) ? $requestData['groupID'] : null;

        // Supondo que a classe Lista tenha os métodos setters apropriados para as propriedades
        $lista = new Lista();
        $lista->setNome_lista($listName);
        $lista->setId_usuario($userID);
        $lista->setId_grupo($groupID);

        // Validar os dados da lista
        $erros = $this->listaService->validarDados($lista);
        if (empty($erros)) {
            try {
                // Inserir a lista no banco de dados
                $this->listaDao->insertLista($lista);

                // Enviar mensagem de sucesso como JSON
                $response = array(
                    'ok' => true,
                    'message' => 'Lista salva com sucesso.'
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

        $listaId = $_GET['id'];

        // Deleta a lista
        $this->listaDao->deleteLista($listaId);

        // Envia a resposta como JSON
        $response = array(
            'message' => 'Lista deletada com sucesso.',
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
$listaCont = new ListaController();
