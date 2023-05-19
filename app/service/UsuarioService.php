<?php
    
require_once(__DIR__ . "/../model/Usuario.php");

class UsuarioService {

    /* Método para validar os dados do usuário que vem do formulário */
    public function validarDados(Usuario $usuario, string $confSenha) {
        $erros = array();

        //Validar campos vazios
        if(! $usuario->getNome())
            array_push($erros, "'Name' is required");

        if(! $usuario->getLogin())
            array_push($erros, "'Login' is required");

        if(! $usuario->getSenha())
            array_push($erros, "'Password' is required");

        if(! $confSenha)
            array_push($erros, "'Password Confirmation' is required");
            
        if(! $usuario->getEmail())
            array_push($erros, "'Email' is required");

        //Validar se a senha é igual a contra senha
        if($usuario->getSenha() && $confSenha && $usuario->getSenha() != $confSenha)
            array_push($erros, "'Password' and 'Password Confirmation' do not match");

        return $erros;
    }

    

}
