<?php
#Nome do arquivo: TarefaDAO.php
#Objetivo: classe DAO para o model de Tarefa

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../connection/Connection.php");
include_once(__DIR__ . "/../model/Tarefa.php");

class TarefaDAO {

    //Método para listar as tarefas a partir da base de dados
    public function listTarefas($id_usuario) {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas WHERE id_usuario = ". $id_usuario ." ORDER BY id_tarefa DESC";
        $stm = $conn->prepare($sql);    
        $stm->execute();
        $result = $stm->fetchAll();
        
        return $this->mapTarefas($result);
    }

    public function findAllTarefas($idUsuario) {
        $conn = Connection::getConn();
    
        $sql = "SELECT * FROM tb_tarefas t WHERE t.id_usuario = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$idUsuario]);
        $result = $stm->fetchAll();
    
        return $this->mapTarefas($result);
    }
    
    //Método para buscar uma tarefa por seu ID
    public function findByIdTarefa(int $id) {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas t" .
               " WHERE t.id_tarefa = ?";
        $stm = $conn->prepare($sql);    
        $stm->execute([$id]);
        $result = $stm->fetchAll();

        $tarefas = $this->mapTarefas($result);

        if(count($tarefas) == 1)
            return $tarefas[0];
        elseif(count($tarefas) == 0)
            return null;

        die("TarefaDAO.findByIdTarefa()" . 
            " - Erro: mais de uma tarefa encontrada.");
    }

    //Método para buscar uma tarefa por seu nome

    public function findByNomeTarefa(string $nome) {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas t" .
               " WHERE t.nome_tarefa = ?";
        $stm = $conn->prepare($sql);    
        $stm->execute([$nome]);
        $result = $stm->fetchAll();

        $tarefas = $this->mapTarefas($result);

        if(count($tarefas) == 1)
            return $tarefas[0];
        elseif(count($tarefas) == 0)
            return null;

        die("TarefaDAO.findByNomeTarefa()" . 
            " - Erro: mais de uma tarefa encontrada.");
    }

    //Método para inserir uma Tarefa
    public function insertTarefa(Tarefa $tarefa) {
        $conn = Connection::getConn();
    
        $sql = "INSERT INTO tb_tarefas (nome_tarefa, descricao, dificuldade, prioridade, concluida, valor_pontos, id_usuario)" . 
               " VALUES (:nome_tarefa, :descricao, :dificuldade, :prioridade, :concluida, :valor_pontos, :id_usuario)";
       
        $stm = $conn->prepare($sql);
        $stm->bindValue(":nome_tarefa", $tarefa->getNome_tarefa());
        $stm->bindValue(":descricao", $tarefa->getDescricao_tarefa());
        $stm->bindValue(":dificuldade", $tarefa->getDificuldade());
        $stm->bindValue(":prioridade", $tarefa->getPrioridade());
        $stm->bindValue(":concluida", $tarefa->getConcluida());
        $stm->bindValue(":valor_pontos", $tarefa->getValor_pontos(), PDO::PARAM_INT);
        $stm->bindValue(":id_usuario", $tarefa->getId_usuario());
        $stm->execute();
    }

    //Método para atualizar uma Tarefa

    public function updateTarefa(Tarefa $tarefa) {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_tarefas SET nome_tarefa = ?, descricao = ?, dificuldade = ?, prioridade = ?, valor_pontos = ? WHERE id_tarefa = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $tarefa->getNome_tarefa());
        $stm->bindValue(2, $tarefa->getDescricao_tarefa());
        $stm->bindValue(3, $tarefa->getDificuldade());
        $stm->bindValue(4, $tarefa->getPrioridade());
        $stm->bindValue(5, $tarefa->getValor_pontos());
        $stm->bindValue(6, $tarefa->getId_tarefa());
        $stm->execute();

    }

    //Método para excluir uma Tarefa pelo seu ID

    public function deleteTarefa(int $id) {
        $conn = Connection::getConn();
    
        $sql = "DELETE FROM tb_tarefas WHERE id_tarefa = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $id);
        $stm->execute();
    }
    

    //Método para mapear os dados de Tarefa para um array

    private function mapTarefas($result) {
        $tarefas = array();

        foreach($result as $row) {
            $tarefa = new Tarefa();
            $tarefa->setId_tarefa($row["id_tarefa"]);
            $tarefa->setNome_tarefa($row["nome_tarefa"]);
            $tarefa->setDescricao_tarefa($row["descricao"]);
            $tarefa->setDificuldade($row["dificuldade"]);
            $tarefa->setPrioridade($row["prioridade"]);
            $tarefa->setValor_pontos($row["valor_pontos"]);
            array_push($tarefas, $tarefa);
        }

        return $tarefas;
    }



}