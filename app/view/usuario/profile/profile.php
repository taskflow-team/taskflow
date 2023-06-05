<?php
#Nome do arquivo: home/index.php
#Objetivo: interface com a página inicial do sistema

require_once(__DIR__ . "/../../include/header.php"); //meta links and botstrap
require_once(__DIR__ . "/../../include/sidebar.php");
?>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/include/global.css">

<div class="pseudo-body">
        <h1><?php echo $dados["usuario"]->getNome() ?></h1>
        <h1><?php echo $dados["usuario"]->getNivel() ?></h1>
        <h1><?php echo $dados["usuario"]->getPontos() ?></h1>
        <h1><?php echo $dados["usuario"]->getTarefas_Concluidas() ?></h1>
        <h1><?php echo $dados["usuario"]->getLogin() ?></h1>

        <form id="frmEditUsuario" method="POST" action="<?= BASEURL ?>/controller/UsuarioController.php?action=edit">
            <div>
                <label for="email">Email</label>
                <input type="email" id="txtEmail" name="email"
                    placeholder="Your email" readonly
                    value="<?php echo $dados["usuario"]->getEmail(); ?>"
                />
                <button id="btnEmail">Edit</button>
            </div>

            <div>
                <label for="senha">Senha</label>
                <input type="password" id="txtPassword" name="senha"
                    placeholder="Password" readonly
                    value="<?php echo $dados["usuario"]->getSenha(); ?>"
                />
                <button id="btnPassword">Edit</button>
            </div>

            <button type="submit" class="btn btn-success">Update Profile</button>
        </form>

        <script>
            const btnEmail = document.querySelector('#btnEmail');
            const EmailInput = document.querySelector('#txtEmail');
            const btnPassword = document.querySelector('#btnPassword');
            const PasswordInput = document.querySelector('#txtPassword');
            
            
            btnPassword.addEventListener("click", (event) => {
                event.preventDefault();
                PasswordInput.removeAttribute('readonly');
            });

            btnEmail.addEventListener('click', (event) => {
                event.preventDefault();
                EmailInput.removeAttribute('readonly');
            }); 
        </script>
    
</div>
