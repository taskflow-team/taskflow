<?php

require_once(__DIR__ . "/../model/Grupo.php");

class GrupoService {
    public function validarDados(Grupo $grupo) {
        $erros = array();

        // Validar campos vazios
        if (!$grupo->getNome()) {
            array_push($erros, "O campo [Nome] é obrigatório.");
        }

        if (!$grupo->getCodigo_convite()) {
            array_push($erros, "O campo [Codigo] é obrigatório.");
        }

        return $erros;
    }
}
