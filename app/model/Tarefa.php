<?php

Class Tarefa {

    private $id_tarefa;
    private $nome_tarefa;
    private $descricao_tarefa;
    private $dificuldade;
    private $prioridade;
    private $valor_pontos;
    private $concluida;

    /**
     * Get the value of valor_pontos
     */ 
    public function getValor_pontos()
    {
        return $this->valor_pontos;
    }

    /**
     * Set the value of valor_pontos
     *
     * @return  self
     */ 
    public function setValor_pontos($valor_pontos)
    {
        $this->valor_pontos = $valor_pontos;

        return $this;
    }

    /**
     * Get the value of prioridade
     */ 
    public function getPrioridade()
    {
        return $this->prioridade;
    }

    /**
     * Set the value of prioridade
     *
     * @return  self
     */ 
    public function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;

        return $this;
    }

    /**
     * Get the value of descricao_tarefa
     */ 
    public function getDescricao_tarefa()
    {
        return $this->descricao_tarefa;
    }

    /**
     * Set the value of descricao_tarefa
     *
     * @return  self
     */ 
    public function setDescricao_tarefa($descricao_tarefa)
    {
        $this->descricao_tarefa = $descricao_tarefa;

        return $this;
    }

    /**
     * Get the value of nome_tarefa
     */ 
    public function getNome_tarefa()
    {
        return $this->nome_tarefa;
    }

    /**
     * Set the value of nome_tarefa
     *
     * @return  self
     */ 
    public function setNome_tarefa($nome_tarefa)
    {
        $this->nome_tarefa = $nome_tarefa;

        return $this;
    }

    /**
     * Get the value of id_tarefa
     */ 
    public function getId_tarefa()
    {
        return $this->id_tarefa;
    }

    /**
     * Set the value of id_tarefa
     *
     * @return  self
     */ 
    public function setId_tarefa($id_tarefa)
    {
        $this->id_tarefa = $id_tarefa;

        return $this;
    }

    /**
     * Get the value of dificuldade
     */ 
    public function getDificuldade()
    {
        return $this->dificuldade;
    }

    /**
     * Set the value of dificuldade
     *
     * @return  self
     */ 
    public function setDificuldade($dificuldade)
    {
        $this->dificuldade = $dificuldade;

        return $this;
    }

    /**
     * Get the value of concluida
     */ 
    public function getConcluida()
    {
        return $this->concluida;
    }

    /**
     * Set the value of concluida
     *
     * @return  self
     */ 
    public function setConcluida($concluida)
    {
        $this->concluida = $concluida;

        return $this;
    }
}