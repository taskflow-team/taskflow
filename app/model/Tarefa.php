<?php

class Tarefa
{

    private $id_tarefa;
    private $nome_tarefa;
    private $descricao_tarefa;
    private $dificuldade;
    private $prioridade;
    private $valor_pontos;
    private $concluida;
    private $data_criacao;
    private $id_usuario;
    private $idtb_listas;
    private $id_grupo;

    public function getData_criacaoFormatted()
    {
        if ($this->data_criacao instanceof DateTime) {
            return $this->data_criacao->format('Y-m-d H:i:s');
        }
        return null;
    }

    /**
     * Get the value of data_criacao
     */
    public function getData_criacao()
    {
        return $this->data_criacao->format('Y-m-d H:i:s');
    }

    /**
     * Set the value of data_criacao
     *
     * @return  self
     */
    public function setData_criacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;

        return $this;
    }

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
     * Get the value of idtb_listas
     */
    public function getIdtb_listas()
    {
        return $this->idtb_listas;
    }

    /**
     * Set the value of idtb_listas
     *
     * @return  self
     */
    public function setIdtb_listas($idtb_listas)
    {
        $this->idtb_listas = $idtb_listas;

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
