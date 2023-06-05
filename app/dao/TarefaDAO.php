<?php
#Nome do arquivo: TarefaDAO.php
#Objetivo: classe DAO para o model de Tarefa

include_once(__DIR__ . "/../connection/Connection.php");
include_once(__DIR__ . "/../model/Tarefa.php");

class TarefaDAO {

    //Método para listar as tarefas a partir da base de dados
    public function listTarefas() {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas t ORDER BY t.nome_tarefa";
        $stm = $conn->prepare($sql);    
        $stm->execute();
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

        $sql = "INSERT INTO tb_tarefas (nome_tarefa, descricao_tarefa, data_inicio, data_fim, status_tarefa, id_usuario) VALUES (?, ?, ?, ?, ?, ?)";
        $stm = $conn->prepare($sql);
        $stm->bindValue("nome_tarefa", $tarefa->getNomeTarefa());
        $stm->bindValue("descricao_tarefa", $tarefa->getDescricaoTarefa());
        $stm->bindValue("dificuldade", $tarefa->getDificuldade());
        $stm->bindValue("prioridade", $tarefa->getPrioridade());
        $stm->bindValue("valor_pontos", $tarefa->getValor_pontos());
        $stm->execute();
    }

    //Método para atualizar uma Tarefa

    public function updateTarefa(Tarefa $tarefa) {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_tarefas SET nome_tarefa = ?, descricao_tarefa = ?, data_inicio = ?, data_fim = ?, status_tarefa = ?, id_usuario = ? WHERE id_tarefa = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue("nome_tarefa", $tarefa->getNomeTarefa());
        $stm->bindValue("descricao_tarefa", $tarefa->getDescricaoTarefa());
        $stm->bindValue("dificuldade", $tarefa->getDificuldade());
        $stm->bindValue("prioridade", $tarefa->getPrioridade());
        $stm->bindValue("valor_pontos", $tarefa->getValor_pontos());
        $stm->bindValue("id_tarefa", $tarefa->getIdTarefa());
        $stm->execute();
    }

    //Método para excluir uma Tarefa pelo seu ID

    public function deleteTarefa(int $id) {
        $conn = Connection::getConn();

        $sql = "DELETE FROM tb_tarefas WHERE id_tarefa = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue("id_tarefa", $id);
        $stm->execute();
    }

    //Método para mapear os dados de Tarefa para um array

    private function mapTarefas($result) {
        $tarefas = array();

        foreach($result as $row) {
            $tarefa = new Tarefa();
            $tarefa->setIdTarefa($row["id_tarefa"]);
            $tarefa->setNomeTarefa($row["nome_tarefa"]);
            $tarefa->setDescricaoTarefa($row["descricao_tarefa"]);
            $tarefa->setDificuldade($row["dificuldade"]);
            $tarefa->setPrioridade($row["prioridade"]);
            $tarefa->setValor_pontos($row["valor_pontos"]);
            $tarefa->setIdUsuario($row["id_usuario"]);
            array_push($tarefas, $tarefa);
        }

        return $tarefas;
    }



}