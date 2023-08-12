<?php

require_once(__DIR__ . "/../model/Lista.php");

class ListaService {
    public function validarDados(Lista $lista) {
        $erros = array();

        // Validar campos vazios
        if (!$lista->getNome_lista()) {
            array_push($erros, "O campo [Nome] é obrigatório.");
        }

        return $erros;
    }
}
