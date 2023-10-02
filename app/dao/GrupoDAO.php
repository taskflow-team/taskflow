<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../configs/Connection.php");
include_once(__DIR__ . "/../model/Grupo.php");

class GrupoDAO {

    private const SQL_USUARIO_GRUPO = "SELECT tb_usuario.id_usuario FROM tb_usuario INNER JOIN tb_grupos_usuarios ON tb_usuario.id_usuario=tb_grupos_usuarios.id_usuario";
    private const SQL_GRUPOS = "SELECT tb_grupo.id_grupo FROM tb_grupos INNER JOIN tb_grupos_usuarios ON tb_grupo.id_grupo=tb_grupos_usuarios.id_grupo";

    public function getUserGrupos($user_id)
    {
        $conn = Connection::getConn();

        $sql = GrupoDAO::SQL_USUARIO_GRUPO . " WHERE id_usuario = " . $user_id . " ;";
        $stm = $conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();

        return $this->mapGrupos($result);
    }

    public function findByIdGrupo($id)
    {
        $conn = Connection::getConn();

        $sql = GrupoDAO::SQL_GRUPOS . " WHERE id_grupo = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$id]);
        $result = $stm->fetchAll();

        $grupos = $this->mapGrupos($result);

        if (count($grupos) == 1) {
            return $grupos[0];
        } elseif (count($grupos) == 0) {
            return null;
        }

        die("ListaDAO.findByIdGrupo() - Error: more than one group found.");
    }

    public function insertGrupo(Grupo $grupo)
    {
        $conn = Connection::getConn();

        $sql = "INSERT INTO tb_grupos (codigo_convite, nome) VALUES (:codigo_convite, :nome)";
        $stm = $conn->prepare($sql);
        $stm->bindValue(":codigo_convite", $grupo->getCodigo_convite());
        $stm->bindValue(":nome", $grupo->getNome());
        $stm->execute();
    }

    public function findGroupByCode($groupCode)
    {
        $conn = Connection::getConn();
    
        $sql = "SELECT * FROM tb_grupos WHERE codigo_convite = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$groupCode]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $grupo = new Grupo();
            $grupo->setId_grupo($result['idtb_grupos']);
            return $grupo;
        }
    
        return null;
    }
    
    public function joinGroup($groupID, $userID)
    {
        $conn = Connection::getConn();
    
        $sql = "INSERT INTO tb_grupos_usuarios (id_grupo, id_usuario, administrador, pontos) VALUES (?, ?, ?, ?)";
        $stm = $conn->prepare($sql);
        $administrador = 0;
        $pontos = 0;
        $stm->execute([$groupID, $userID, $administrador, $pontos]);
    
        return $stm->rowCount() > 0;
    }
    
    public function isUserMemberOfGroup($groupCode, $userID)
    {
        $conn = Connection::getConn();

        $sql = "SELECT COUNT(*) AS count
                FROM tb_grupos AS g
                INNER JOIN tb_grupos_usuarios AS gu ON g.idtb_grupos = gu.id_grupo
                WHERE g.codigo_convite = :groupCode
                AND gu.id_usuario = :userID";

        $stm = $conn->prepare($sql);
        $stm->bindValue(':groupCode', $groupCode);
        $stm->bindValue(':userID', $userID);
        $stm->execute();

        $result = $stm->fetch();

        // Se o contador for maio que 0, usuário é membro do grupo.
        return $result['count'] > 0;
    }
        
    public function updateGrupo(Grupo $grupo)
    {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_grupos SET nome = ? WHERE idtb_grupos = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $grupo->getNome());
        $stm->bindValue(2, $grupo->getIdtbGrupo());
        $stm->execute();
    }

    public function deleteGrupo(int $id)
    {
        $conn = Connection::getConn();

        $sql = "DELETE FROM tb_grupos_usuarios WHERE id_grupo = ?; DELETE FROM tb_grupos WHERE idtb_grupos = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $id);
        $stm->bindValue(2, $id);
        $stm->execute();
    }


    private function mapGrupos($result) {
        $grupos = array();
    
        foreach ($result as $row) {
            $grupo = new Grupo();
            $grupo->setIdtbGrupo($row["idtb_grupo"]);
            $grupo->setId_grupo($row["id_grupo"]);
            $grupo->setId_usuario($row["id_usuario"]);
            $grupo->setAdministrador($row["administrador"]);
            $grupo->setPontos($row["pontos"]);
            $grupo->setCodigo_convite($row["codigo_convite"]);
            $grupo->setNome($row["nome"]);
            array_push($grupos, $grupo);
        }
    
        return $grupos;
    }
    
}
