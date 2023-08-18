<?php

Class Lista implements JsonSerializable
{
    private $id_lista;
    private $nome_lista;
    private $id_usuario;
    private $id_grupo;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            "id_lista" => $this->id_lista,
            "nome_lista" => $this->nome_lista,
        ];
    }
    
    /**
     * Get the value of id_lista
     */ 
    public function getId_lista()
    {
        return $this->id_lista;
    }

    /**
     * Set the value of id_lista
     *
     * @return  self
     */ 
    public function setId_lista($id_lista)
    {
        $this->id_lista = $id_lista;

        return $this;
    }

    /**
     * Get the value of nome_lista
     */ 
    public function getNome_lista()
    {
        return $this->nome_lista;
    }

    /**
     * Set the value of nome_lista
     *
     * @return  self
     */ 
    public function setNome_lista($nome_lista)
    {
        $this->nome_lista = $nome_lista;

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
}