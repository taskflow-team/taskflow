<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../configs/Connection.php");
include_once(__DIR__ . "/../model/Grupo.php");

class GrupoDAO {

    private const SQL_USUARIO_GRUPO = "SELECT tb_usuarios.id_usuario FROM tb_usuarios INNER JOIN tb_grupos_usuarios ON tb_usuarios.id_usuario=tb_grupos_usuarios.id_usuario";
    private const SQL_GRUPOS = "SELECT tb_grupos.id_grupo FROM tb_grupos INNER JOIN tb_grupos_usuarios ON tb_grupos.id_grupo=tb_grupos_usuarios.id_grupo";

    public function getGroupUserPoints($groupId, $userId) {
        $conn = Connection::getConn();

        $sql = "SELECT pontos FROM tb_grupos_usuarios WHERE id_grupo = ? AND id_usuario = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$groupId, $userId]);
        $result = $stm->fetch();

        return $result ? $result['pontos'] : 0;
    }

    public function updateGroupUserPoints($groupId, $userId, $points) {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_grupos_usuarios SET pontos = pontos + ? WHERE id_grupo = ? AND id_usuario = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$points, $groupId, $userId]);
    }

    public function hasOtherAdministrators($groupId, $userId)
    {
        $conn = Connection::getConn();
    
        $sql = "SELECT COUNT(*) AS admin_count FROM tb_grupos_usuarios WHERE id_grupo = ? AND id_usuario != ? AND administrador = 1";
        $stm = $conn->prepare($sql);
        $stm->execute([$groupId, $userId]);
        $result = $stm->fetch();
    
        return $result && $result['admin_count'] > 0;
    }    

    public function updateGroupAdmin($groupId, $newAdminId)
    {
        $conn = Connection::getConn();

        // Primeiro, define todos os usuários como não administradores
        $sql = "UPDATE tb_grupos_usuarios SET administrador = 0 WHERE id_grupo = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$groupId]);

        // Em seguida, define o novo administrador
        $sql = "UPDATE tb_grupos_usuarios SET administrador = 1 WHERE id_grupo = ? AND id_usuario = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$groupId, $newAdminId]);
    }

    public function selectRandomMemberAsAdmin($groupId)
    {
        $conn = Connection::getConn();

        $sql = "SELECT id_usuario FROM tb_grupos_usuarios WHERE id_grupo = ? AND administrador = 0 ORDER BY RAND() LIMIT 1";
        $stm = $conn->prepare($sql);
        $stm->execute([$groupId]);
        $result = $stm->fetch();

        return $result ? $result['id_usuario'] : null;
    }

    public function isUserAdmin($groupId, $userId)
    {
        $conn = Connection::getConn();

        $sql = "SELECT administrador FROM tb_grupos_usuarios WHERE id_grupo = ? AND id_usuario = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$groupId, $userId]);
        $result = $stm->fetch();

        return $result && $result['administrador'] == 1;
    }

    public function getUserGrupos($user_id)
    {   	
        $conn = Connection::getConn();
    
        $sql = "SELECT g.*, gu.administrador, gu.pontos, gu.id_grupo
                FROM tb_grupos g
                INNER JOIN tb_grupos_usuarios gu ON g.idtb_grupos = gu.id_grupo
                WHERE gu.id_usuario = :user_id";
    
        $stm = $conn->prepare($sql);
        $stm->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    
        $grupos = $this->mapGrupos($result);
    
        return $grupos;
    } 

    public function getUsersInGroup($groupId)
    {
        $conn = Connection::getConn();

        $sql = "SELECT u.* FROM tb_usuarios u
                INNER JOIN tb_grupos_usuarios gu ON u.id_usuario = gu.id_usuario
                WHERE gu.id_grupo = :groupId";

        $stm = $conn->prepare($sql);
        $stm->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByIdGrupo($id)
    {
        $conn = Connection::getConn();
    
        $sql = "SELECT * FROM tb_grupos WHERE idtb_grupos = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$id]);
        $result = $stm->fetchAll();
    
        $grupos = $this->mapGrupos($result);
    
        if (count($grupos) == 1) {
            return $grupos[0];
        } elseif (count($grupos) == 0) {
            return null;
        }
    
        die("GrupoDAO.findByIdGrupo() - Error: more than one group found.");
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
    
    public function joinGroup($groupID, $userID, $administrador)
    {
        $conn = Connection::getConn();
    
        $sql = "INSERT INTO tb_grupos_usuarios (id_grupo, id_usuario, administrador) VALUES (?, ?, ?)";
        $stm = $conn->prepare($sql);
        $stm->execute([$groupID, $userID, $administrador]);
    
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

    public function leaveGrupo(int $groupId, int $userId)
    {
        $conn = Connection::getConn();

        $sql = "DELETE FROM tb_grupos_usuarios WHERE id_grupo = ? AND id_usuario = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $groupId);
        $stm->bindValue(2, $userId);
        $stm->execute();
    }

    private function mapGrupos($result)
    {
        $grupos = array();
    
        foreach ($result as $row) {
            $grupo = new Grupo();
    
            if (isset($row['idtb_grupos'])) {
                $grupo->setIdtbGrupo($row['idtb_grupos']);
            }
    
            if (isset($row['id_grupo'])) {
                $grupo->setId_grupo($row['id_grupo']);
            }
    
            if (isset($row['id_usuario'])) {
                $grupo->setId_usuario($row['id_usuario']);
            }
    
            if (isset($row['administrador'])) {
                $grupo->setAdministrador($row['administrador']);
            }
    
            if (isset($row['pontos'])) {
                $grupo->setPontos($row['pontos']);
            }
    
            if (isset($row['codigo_convite'])) {
                $grupo->setCodigo_convite($row['codigo_convite']);
            }
    
            if (isset($row['nome'])) {
                $grupo->setNome($row['nome']);
            }
    
            array_push($grupos, $grupo);
        }
    
        return $grupos;
    }
    
    
}
