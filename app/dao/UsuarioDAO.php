<?php
#Nome do arquivo: UsuarioDAO.php
#Objetivo: classe DAO para o model de Usuario

include_once(__DIR__ . "/../configs/Connection.php");
include_once(__DIR__ . "/../model/Usuario.php");

class UsuarioDAO
{
    public function getUsersByIds($userIds)
    {
        if (empty($userIds)) {
            return [];
        }
    
        $conn = Connection::getConn();
    
        $inQuery = implode(',', array_fill(0, count($userIds), '?'));
        $sql = "SELECT * FROM tb_usuarios WHERE id_usuario IN ($inQuery)";
    
        $stm = $conn->prepare($sql);
        foreach ($userIds as $k => $id) {
            $stm->bindValue(($k+1), $id, PDO::PARAM_INT);
        }
        $stm->execute();
    
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    //Método para listar os usuaários a partir da base de dados
    public function list()
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_usuarios u ORDER BY u.nome_usuario";
        $stm = $conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();

        return $this->mapUsuarios($result);
    }

    //Método para buscar um usuário por seu ID
    public function findById(int $id)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_usuarios u" .
            " WHERE u.id_usuario = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$id]);
        $result = $stm->fetchAll();

        $usuarios = $this->mapUsuarios($result);

        if (count($usuarios) == 1)
            return $usuarios[0];
        elseif (count($usuarios) == 0)
            return null;

        die("UsuarioDAO.findById()" .
            " - Erro: mais de um usuário encontrado.");
    }


    //Método para buscar um usuário por seu login e senha
    public function findByLoginSenha(string $login, string $senha)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_usuarios u" .
            " WHERE u.login = ? AND u.senha = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$login, $senha]);
        $result = $stm->fetchAll();

        $usuarios = $this->mapUsuarios($result);

        if (count($usuarios) == 1)
            return $usuarios[0];
        elseif (count($usuarios) == 0)
            return null;

        die("UsuarioDAO.findByLoginSenha()" .
            " - Erro: mais de um usuário encontrado.");
    }

    //Método para inserir um Usuario
    public function insert(Usuario $usuario)
    {
        $conn = Connection::getConn();

        $sql = "INSERT INTO tb_usuarios (nome_usuario, login, senha, email, nivel, pontos, tarefas_concluidas, foto_perfil)" .
        " VALUES (:nome, :login, :senha, :email, :nivel, :pontos, :tarefas_concluidas, :foto_perfil)";

        $stm = $conn->prepare($sql);
        $stm->bindValue("nome", $usuario->getNome());
        $stm->bindValue("login", $usuario->getLogin());
        $stm->bindValue("senha", $usuario->getSenha());
        $stm->bindValue("email", $usuario->getEmail());
        $stm->bindValue("nivel", $usuario->getNivel());
        $stm->bindValue("pontos", $usuario->getPontos());
        $stm->bindValue("tarefas_concluidas", $usuario->getTarefas_concluidas());
        $stm->bindValue("foto_perfil", $usuario->getFotoPerfil());
        $stm->execute();
    }

    // Método para atualizar um Usuario
    public function update(Usuario $usuario)
    {
        $conn = Connection::getConn();

        // Calcular o nivel com base nas tarefas concluidas
        $nivel = $this->calcularNivel($usuario->getTarefas_concluidas());

        $sql = "UPDATE tb_usuarios SET email = :email, senha = :senha, pontos = :pontos, tarefas_concluidas = :tarefas_concluidas, nivel = :nivel, foto_perfil = :foto_perfil WHERE id_usuario = :id";

        $stm = $conn->prepare($sql);
        $stm->bindValue("senha", $usuario->getSenha());
        $stm->bindValue("email", $usuario->getEmail());
        $stm->bindValue("id", $usuario->getId());
        $stm->bindValue("pontos", $usuario->getPontos());
        $stm->bindValue("tarefas_concluidas", $usuario->getTarefas_concluidas());
        $stm->bindValue("nivel", $nivel);
        $stm->bindValue("foto_perfil", $usuario->getFotoPerfil());
        $stm->execute();
    }

    // Método para calcular o nivel com base nas tarefas concluidas
    public function calcularNivel($completedTasks)
    {
        $baseValue = 10;

        $nivel = floor(log($completedTasks / $baseValue, 2));

        $nivel = max(0, $nivel);

        return $nivel;
    }

    //Método para excluir um Usuario pelo seu ID
    public function deleteById(int $id)
    {
        $conn = Connection::getConn();

        $sql = "DELETE FROM tb_usuarios WHERE id_usuario = :id";

        $stm = $conn->prepare($sql);
        $stm->bindValue("id", $id);
        $stm->execute();
    }

    public function updateProfileImage($userId, $fileName)
    {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_usuarios SET foto_perfil = :foto_perfil WHERE id_usuario = :id";

        $stm = $conn->prepare($sql);
        $stm->bindValue(":foto_perfil", $fileName);
        $stm->bindValue(":id", $userId, PDO::PARAM_INT);
        $stm->execute();
    }

    //Método para converter um registro da base de dados em um objeto Usuario
    private function mapUsuarios($result)
    {
        $usuarios = array();
        foreach ($result as $reg) {
            $usuario = new Usuario();
            $usuario->setId($reg['id_usuario']);
            $usuario->setNome($reg['nome_usuario']);
            $usuario->setLogin($reg['login']);
            $usuario->setSenha($reg['senha']);
            $usuario->setEmail($reg['email']);
            $usuario->setPontos($reg['pontos']);
            $usuario->setNivel($reg['nivel']);
            $usuario->setTarefas_concluidas($reg['tarefas_concluidas']);
            $usuario->setFotoPerfil($reg['foto_perfil']);
            array_push($usuarios, $usuario);
        }

        return $usuarios;
    }
}
