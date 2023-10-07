<?php

Class Grupo
{
    private $idtb_grupo;
    private $id_grupos_usuarios;
    private $codigo_convite;
    private $nome;
    private $id_usuario;
    private $id_grupo;
    private $administrador;
    private $pontos;


    /**
     * Get the value of id_grupos_usuarios
     */ 
    public function getId_grupos_usuarios()
    {
        return $this->id_grupos_usuarios;
    }

    /**
     * Set the value of id_grupos_usuarios
     *
     * @return  self
     */ 
    public function setId_grupos_usuarios($id_grupos_usuarios)
    {
        $this->id_grupos_usuarios = $id_grupos_usuarios;

        return $this;
    }

    /**
     * Get the value of codigo_convite
     */ 
    public function getCodigo_convite()
    {
        return $this->codigo_convite;
    }

    /**
     * Set the value of codigo_convite
     *
     * @return  self
     */ 
    public function setCodigo_convite($codigo_convite)
    {
        $this->codigo_convite = $codigo_convite;

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
     * Get the value of id_usuario
     */ 
    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    /**
     * Set the value of id_usuario
     *
     * @return  self
     */ 
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    /**
     * Get the value of id_grupo
     */ 
    public function getId_grupo()
    {
        return $this->id_grupo;
    }

    /**
     * Set the value of id_grupo
     *
     * @return  self
     */ 
    public function setId_grupo($id_grupo)
    {
        $this->id_grupo = $id_grupo;

        return $this;
    }

    /**
     * Get the value of administrador
     */ 
    public function getAdministrador()
    {
        return $this->administrador;
    }

    /**
     * Set the value of administrador
     *
     * @return  self
     */ 
    public function setAdministrador($administrador)
    {
        $this->administrador = $administrador;

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
     * Get the value of idtb_grupo
     */
    public function getIdtbGrupo()
    {
        return $this->idtb_grupo;
    }

    /**
     * Set the value of idtb_grupo
     */
    public function setIdtbGrupo($idtb_grupo)
    {
        $this->idtb_grupo = $idtb_grupo;

        return $this;
    }
}