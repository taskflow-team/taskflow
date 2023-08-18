<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../configs/Connection.php");
include_once(__DIR__ . "/../model/Lista.php");

class ListaDAO {

    public function getUserLists($user_id)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_listas WHERE idtb_usuario = " . $user_id . " ;";
        $stm = $conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();

        return $this->mapListas($result);
    }

    public function findByIdLista($id)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_listas WHERE idtb_listas = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$id]);
        $result = $stm->fetchAll();

        $listas = $this->mapListas($result);

        if (count($listas) == 1) {
            return $listas[0];
        } elseif (count($listas) == 0) {
            return null;
        }

        die("ListaDAO.findByIdLista() - Error: more than one list found.");
    }

    public function insertLista(Lista $lista)
    {
        $conn = Connection::getConn();

        $sql = "INSERT INTO tb_listas (nome, idtb_usuario) VALUES (:nome, :idtb_usuario)";
        $stm = $conn->prepare($sql);
        $stm->bindValue(":nome", $lista->getNome_lista());
        $stm->bindValue(":idtb_usuario", $lista->getId_usuario());
        $stm->execute();
    }

    public function updateLista(Lista $lista)
    {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_listas SET nome = ? WHERE idtb_listas = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $lista->getNome_lista());
        $stm->bindValue(2, $lista->getId_lista());
        $stm->execute();
    }

    public function deleteLista(int $id)
    {
        $conn = Connection::getConn();

        $sql = "DELETE FROM tb_listas WHERE idtb_listas = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $id);
        $stm->execute();
    }


    private function mapListas($result) {
        $listas = array();

        foreach ($result as $row) {
            $lista = new Lista();
            $lista->setId_lista($row["idtb_listas"]);
            $lista->setNome_lista($row["nome"]);
            array_push($listas, $lista);
        }

        return $listas;
    }
}
