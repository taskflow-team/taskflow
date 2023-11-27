<?php
#Nome do arquivo: TarefaDAO.php
#Objetivo: classe DAO para o model de Tarefa

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../configs/Connection.php");
include_once(__DIR__ . "/../model/Tarefa.php");

class TarefaDAO
{

    //Método para listar as tarefas a partir da base de dados
    public function listTarefas($id_usuario, $idtb_listas, $rule)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas WHERE id_usuario = " . $id_usuario . " AND idtb_listas = " . $idtb_listas . " ORDER BY " . $rule . " DESC";
        $stm = $conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();

        return $this->mapTarefas($result);
    }

    public function listTarefasGrupo($id_grupo, $idtb_listas, $rule)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas WHERE id_grupo = " . $id_grupo . " AND idtb_listas = " . $idtb_listas . " ORDER BY " . $rule . " DESC";
        $stm = $conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();

        return $this->mapTarefas($result);
    }

    public function searchTarefas($id_usuario, $idtb_listas, $searchQuery, $rule)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas WHERE (nome_tarefa LIKE :searchTerm OR descricao LIKE :searchTerm) AND idtb_listas = :idtb_listas AND id_usuario = :id_usuario ORDER BY :rule DESC";
        $stm = $conn->prepare($sql);

        $searchTerm = "%" . $searchQuery . "%";
        $stm->bindValue(":searchTerm", $searchTerm);
        $stm->bindValue(":idtb_listas", $idtb_listas);
        $stm->bindValue(":id_usuario", $id_usuario);
        $stm->bindValue(":rule", $rule);

        $stm->execute();
        $result = $stm->fetchAll();

        return $this->mapTarefas($result);
    }

    public function searchTarefasGrupo($id_grupo, $idtb_listas, $searchQuery, $rule)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas WHERE (nome_tarefa LIKE :searchTerm OR descricao LIKE :searchTerm) AND idtb_listas = :idtb_listas AND id_grupo = :id_grupo ORDER BY :rule DESC";
        $stm = $conn->prepare($sql);

        $searchTerm = "%" . $searchQuery . "%";
        $stm->bindValue(":searchTerm", $searchTerm);
        $stm->bindValue(":idtb_listas", $idtb_listas);
        $stm->bindValue(":id_grupo", $id_grupo);
        $stm->bindValue(":rule", $rule);

        $stm->execute();
        $result = $stm->fetchAll();

        return $this->mapTarefas($result);
    }

    public function findAllTarefas($idUsuario)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas t WHERE t.id_usuario = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$idUsuario]);
        $result = $stm->fetchAll();

        return $this->mapTarefas($result);
    }

    //Método para buscar uma tarefa por seu ID
    public function findByIdTarefa(int $id)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas t" .
            " WHERE t.id_tarefa = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$id]);
        $result = $stm->fetchAll();

        $tarefas = $this->mapTarefas($result);

        if (count($tarefas) == 1)
            return $tarefas[0];
        elseif (count($tarefas) == 0)
            return null;

        die("TarefaDAO.findByIdTarefa()" .
            " - Erro: mais de uma tarefa encontrada.");
    }

    //Método para buscar uma tarefa por seu nome

    public function findByNomeTarefa(string $nome)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_tarefas t" .
            " WHERE t.nome_tarefa = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$nome]);
        $result = $stm->fetchAll();

        $tarefas = $this->mapTarefas($result);

        if (count($tarefas) == 1)
            return $tarefas[0];
        elseif (count($tarefas) == 0)
            return null;

        die("TarefaDAO.findByNomeTarefa()" .
            " - Erro: mais de uma tarefa encontrada.");
    }

    //Método para inserir uma Tarefa
    public function insertTarefa(Tarefa $tarefa)
    {
        $conn = Connection::getConn();

        $sql = "INSERT INTO tb_tarefas (nome_tarefa, descricao, dificuldade, prioridade, valor_pontos, data_criacao, id_usuario, idtb_listas, id_grupo)" .
            " VALUES (:nome_tarefa, :descricao, :dificuldade, :prioridade, :valor_pontos, :data_criacao, :id_usuario, :idtb_listas, :id_grupo)";

        $stm = $conn->prepare($sql);
        $stm->bindValue(":nome_tarefa", $tarefa->getNome_tarefa());
        $stm->bindValue(":descricao", $tarefa->getDescricao_tarefa());
        $stm->bindValue(":dificuldade", $tarefa->getDificuldade());
        $stm->bindValue(":prioridade", $tarefa->getPrioridade());
        $stm->bindValue(":valor_pontos", $tarefa->getValor_pontos(), PDO::PARAM_INT);
        $stm->bindValue(":data_criacao", $tarefa->getData_criacaoFormatted());
        $stm->bindValue(":id_usuario", $tarefa->getId_usuario());
        $stm->bindValue(":idtb_listas", $tarefa->getIdtb_listas());
        $stm->bindValue(":id_grupo", $tarefa->getId_grupo());
        $stm->execute();
    }

    //Método para atualizar uma Tarefa

    public function updateTarefa(Tarefa $tarefa)
    {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_tarefas SET nome_tarefa = ?, descricao = ?, dificuldade = ?, prioridade = ?, valor_pontos = ?, data_criacao = ?, concluida = ?, idtb_listas = ?, id_grupo = ? WHERE id_tarefa = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $tarefa->getNome_tarefa());
        $stm->bindValue(2, $tarefa->getDescricao_tarefa());
        $stm->bindValue(3, $tarefa->getDificuldade());
        $stm->bindValue(4, $tarefa->getPrioridade());
        $stm->bindValue(5, $tarefa->getValor_pontos());
        $stm->bindValue(6, $tarefa->getData_criacao());
        $stm->bindValue(7, $tarefa->getConcluida());
        $stm->bindValue(8, $tarefa->getIdtb_listas());
        $stm->bindValue(9, $tarefa->getId_grupo());
        $stm->bindValue(10, $tarefa->getId_tarefa());
        $stm->execute();
    }


    //Método para excluir uma Tarefa pelo seu ID

    public function deleteTarefa(int $id)
    {
        $conn = Connection::getConn();

        $sql = "DELETE FROM tb_tarefas WHERE id_tarefa = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $id);
        $stm->execute();
    }


    //Método para mapear os dados de Tarefa para um array

    private function mapTarefas($result)
    {
        $tarefas = array();

        foreach ($result as $row) {
            $tarefa = new Tarefa();
            $tarefa->setId_tarefa($row["id_tarefa"]);
            $tarefa->setNome_tarefa($row["nome_tarefa"]);
            $tarefa->setDescricao_tarefa($row["descricao"]);
            $tarefa->setDificuldade($row["dificuldade"]);
            $tarefa->setPrioridade($row["prioridade"]);
            $tarefa->setValor_pontos($row["valor_pontos"]);
            $date_criacao = new DateTime($row["data_criacao"] ?? '');
            $tarefa->setData_criacao($date_criacao);
            $tarefa->setConcluida($row["concluida"]);
            $tarefa->setIdtb_listas($row["idtb_listas"]);
            $tarefa->setId_grupo($row["id_grupo"]);
            array_push($tarefas, $tarefa);
        }

        return $tarefas;
    }
}
