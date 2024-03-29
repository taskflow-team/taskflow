<?php
#Nome do arquivo: Usuario.php
#Objetivo: classe Model para Usuario

class Usuario implements JsonSerializable
{

    private $id;
    private $nome;
    private $login;
    private $senha;
    private $email;
    private $pontos;
    private $nivel;
    private $tarefas_concluidas;
    private $foto_perfil;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "nome" => $this->nome,
            "login" => $this->login,
            "senha" => $this->senha,
            "email" => $this->email,
            "pontos" => $this->pontos,
            "nivel" => $this->nivel,
            "tarefas_concluidas" => $this->tarefas_concluidas
        ];
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set the value of login
     *
     * @return  self
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get the value of nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of senha
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set the value of senha
     *
     * @return  self
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * Get the value of pontos
     */
    public function getPontos()
    {
        return $this->pontos;
    }

    /**
     * Set the value of pontos
     *
     * @return  self
     */
    public function setPontos($pontos)
    {
        $this->pontos = $pontos;

        return $this;
    }

    /**
     * Get the value of tarefas_concluidas
     */
    public function getTarefas_concluidas()
    {
        return $this->tarefas_concluidas;
    }

    /**
     * Set the value of tarefas_concluidas
     *
     * @return  self
     */
    public function setTarefas_concluidas($tarefas_concluidas)
    {
        $this->tarefas_concluidas = $tarefas_concluidas;

        return $this;
    }

    /**
     * Get the value of nivel
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set the value of nivel
     *
     * @return  self
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of foto_perfil
     */
    public function getFotoPerfil() {
        return $this->foto_perfil;
    }

    /**
     * Set the value of foto_perfil
     *
     * @return  self
     */
    public function setFotoPerfil($foto_perfil) {
        $this->foto_perfil = $foto_perfil;
        return $this;
    }
}
