<?php
    require_once(__DIR__ . "/../../include/header.php"); //meta links and botstrap
    require_once(__DIR__ . "/../../include/sidebar.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/include/global.css">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/usuario/profile/profile.css">

<div class="pseudo-body">
        <div class="profile-holder">
            <img src="../view/img/profile.png" alt="">
            <h1><?php echo $dados["usuario"]->getLogin() ?></h1>
            <h2><?php echo $dados["usuario"]->getNome() ?></h2>
            <h2><?php echo $dados["usuario"]->getNivel() ?></h2>
            <p>Finished tasks: <?php echo $dados["usuario"]->getTarefas_Concluidas() ?></p>
            
            <form id="frmEditUsuario" method="POST" action="<?= BASEURL ?>/controller/UsuarioController.php?action=edit">
                <label for="email">Email:</label>
                <div>
                    <input type="email" id="txtEmail" name="email"
                        placeholder="Your email" readonly
                        value="<?php echo $dados["usuario"]->getEmail(); ?>"
                    />
                    <button class="btn edit" id="btnEmail">Edit</button>
                </div>

                <label for="senha">Senha:</label>
                <div>
                    <input type="password" id="txtPassword" name="senha"
                        placeholder="Password" readonly
                        value="<?php echo $dados["usuario"]->getSenha(); ?>"
                    />
                    <button class="btn edit" id="btnPassword">Edit</button>
                </div>

                <button type="submit" class="btn btn-success" id="btnSubmit">Update Profile</button>
            </form>
        </div>
</div>

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