<?php

Class Lista implements JsonSerializable
{
    private $id_lista;
    private $nome_lista;

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
}