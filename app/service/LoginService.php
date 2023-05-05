<?php
#Nome do arquivo: LoginService.php
#Objetivo: classe service para Login

require_once(__DIR__ . "/../model/Usuario.php");

class LoginService {

    public function validarCampos(string $login, string $senha) {
        $arrayMsg = array();

        //Valida o campo nome
        if(! $login)
            array_push($arrayMsg, "O campo [Login] é obrigatório.");

        //Valida o campo login
        if(! $senha)
            array_push($arrayMsg, "O campo [Senha] é obrigatório.");

        return $arrayMsg;
    }

}