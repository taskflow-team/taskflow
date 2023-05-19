<?php
#Nome do arquivo: LoginService.php
#Objetivo: classe service para Login

require_once(__DIR__ . "/../model/Usuario.php");

class LoginService {

    public function validarCampos(string $login, string $senha) {
        $arrayMsg = array();

        //Valida o campo nome
        if(! $login)
            array_push($arrayMsg, "'Login' is required");

        //Valida o campo login
        if(! $senha)
            array_push($arrayMsg, "'Password' is required");

        return $arrayMsg;
    }

}