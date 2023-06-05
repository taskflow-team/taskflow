<?php
#Classe controller para Tarefa
require_once(__DIR__ . "/../model/Tarefa.php");
require_once(__DIR__ . "/../dao/TarefaDAO.php");
require_once(__DIR__ . "/../service/TarefaService.php");
require_once(__DIR__ . "/Controller.php");

class TarefaController extends Controller {

    private TarefaDAO $tarefaDao;
    private TarefaService $tarefaService;

    public function __construct() {
        $this->tarefaDao = new TarefaDAO();
        $this->tarefaService = new TarefaService();

        $this->setActionDefault("edit");

        $this->handleAction();
    }

    protected function create() {
        $dados["id"] = 0;
        $this->loadView("tarefa/form.php", $dados);
    }

    protected function edit() {
        if(! $this->usuarioLogado())
        exit;

        $tarefa = $this->findTarefaById();
        $dados["tarefa"] = $tarefa;
        $this->loadView("/tarefa/form.php", $dados);

    }

    protected function save() {
        //Captura os dados do formulário
        $dados["id"] = isset($_POST['id']) ? $_POST['id'] : 0;
        $nome = isset($_POST['nome']) ? trim($_POST['nome']) : NULL;
        $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : NULL;
        $dificuldade = isset($_POST['dificuldade']) ? trim($_POST['dificuldade']) : NULL;
        $prioridade = isset($_POST['prioridade']) ? trim($_POST['prioridade']) : NULL;
        $concluida = isset($_POST['concluida']) ? trim($_POST['concluida']) : NULL;
        $valor_pontos = isset($_POST['valor_pontos']) ? trim($_POST['valor_pontos']) : NULL;
        $usuario_id = isset($_POST['usuario_id']) ? trim($_POST['usuario_id']) : NULL;
        
        //Cria objeto Tarefa
        $tarefa = new Tarefa();
        $tarefa->setNome($nome);
        $tarefa->setDescricao($descricao);
        $tarefa->setDificuldade($dificuldade);
        $tarefa->setPrioridade($prioridade);
        $tarefa->setValor_pontos($valor_pontos);
    }
}