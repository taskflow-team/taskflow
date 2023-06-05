<?php

require_once(__DIR__ . "/../model/Tarefa.php");

class TarefaService {

    /* Método para validar os dados do usuário que vem do formulário */
    public function validarDados(Tarefa $tarefa) {
        $erros = array();

        //Validar campos vazios
        if(! $tarefa->getNome())
            array_push($erros, "O campo [Nome] é obrigatório.");

        if(! $tarefa->getDescricao())
            array_push($erros, "O campo [Descrição] é obrigatório.");

        if(! $tarefa->getPrioridade())
            array_push($erros, "O campo [Prioridade] é obrigatório.");
            
        if(! $tarefa->getDificuldade())
            array_push($erros, "O campo [Dificuldade] é obrigatório.");
        
        if(! $tarefa->getValor_pontos())
            array_push($erros, "O campo [Valor de Pontos] é obrigatório.");

        return $erros;
    }
}