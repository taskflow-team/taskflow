<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../model/Tarefa.php");

class TarefaService
{
    public function validarDados(Tarefa $tarefa)
    {
        $erros = array();

        //Validar campos vazios
        if (!$tarefa->getNome_tarefa())
            array_push($erros, "O campo [Nome] é obrigatório.");

        return $erros;
    }
}
