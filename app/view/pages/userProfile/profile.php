<?php
    require_once(__DIR__ . "/../../include/header.php"); //meta links and botstrap
    require_once(__DIR__ . "/../../include/sidebar.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/css/global.css">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/pages/userProfile/profile.css">

<div class="pseudo-body">
        <div class="profile-holder">
            <img src="../view/assets/img/profile.png" alt="profile picture">
            <h1><?php echo $dados["usuario"]->getLogin() ?></h1>
            <h2><?php echo $dados["usuario"]->getNome() ?></h2>
            <h2><?php echo $dados["usuario"]->getNivel() ?></h2>
            <p>Finished tasks: <?php echo $dados["usuario"]->getTarefas_Concluidas() ?></p>

            <form id="frmEditUsuario" method="POST" action="<?= BASEURL ?>/controller/UsuarioController.php?action=edit">
                <label for="email">Email:</label>
                <div>
                    <input
                        class="dark-input"
                        type="email"
                        id="txtEmail"
                        name="email"
                        placeholder="Your email" readonly
                        required
                        value="<?php echo $dados["usuario"]->getEmail(); ?>"
                    />
                    <button class="btn edit" id="btnEmail">Edit</button>
                </div>

                <label for="senha">Password:</label>
                <div>
                    <input
                        class="dark-input"
                        type="password"
                        id="txtPassword"
                        name="senha"
                        placeholder="Password" readonly
                        required
                        value="<?php echo $dados["usuario"]->getSenha(); ?>"
                    />
                    <img src="../view/assets/icons/eye.png" alt="eye icon" class="eyeIcon">
                    <button class="btn edit" id="btnPassword">Edit</button>
                </div>

                <input type="hidden" name="id" value="<?php echo $dados["usuario"]->getId(); ?>" />

                <?php
                    if (isset($msgSucesso) && trim($msgSucesso) != "") {
                        echo "<div class='alert alert-success'>" . $msgSucesso . "</div>";
                    }
                ?>

                <button type="submit" class="btn btn-success" id="btnSubmit">Update Profile</button>
            </form>
        </div>
</div>

<script>
    const btnEmail = document.querySelector('#btnEmail');
    const emailInput = document.querySelector('#txtEmail');
    const btnPassword = document.querySelector('#btnPassword');
    const passwordInput = document.querySelector('#txtPassword');
    const btnShowPassword = document.querySelector('.eyeIcon');

    btnPassword.addEventListener("click", (event) => {
        event.preventDefault();
        passwordInput.removeAttribute('readonly');
    });

    btnEmail.addEventListener('click', (event) => {
        event.preventDefault();
        emailInput.removeAttribute('readonly');
    });

    btnShowPassword.addEventListener('click', (event) => {
        event.preventDefault();

        if(passwordInput.getAttribute('type')){
            passwordInput.removeAttribute('type');
        } else {
            passwordInput.setAttribute('type', 'password');
        }
    })
</script>
