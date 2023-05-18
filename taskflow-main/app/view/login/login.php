<?php
#Nome do arquivo: login/login.php
#Objetivo: interface para logar no sistema

require_once(__DIR__ . "/../include/header.php");
?>
<link rel="stylesheet" type="text/css" href="../view/css/login.css">

<div class="login-image">
    <img src="../view/img/login.jpg" alt="Image">
</div>

<div class="container">
    <div class="login-form">
        <div class="login-logo">
            <img src="../view/img/logo.png" alt="Logo">
        </div>
            <h4>Sign in:</h4>
            <br>

            <!-- Formulário de login -->
            <form id="frmLogin" action="./LoginController.php?action=logon" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" name="login" id="txtLogin"
                           maxlength="15" placeholder="e-mail"
                           value="<?php echo isset($dados['login']) ? $dados['login'] : '' ?>" />
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="senha" id="txtSenha"
                           maxlength="15" placeholder="password"
                           value="<?php echo isset($dados['senha']) ? $dados['senha'] : '' ?>" />
                </div>
                <div>
                    <a href="#">Forget your password?</a>
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>
                <div>
                    <p>Don´t have an account? <a href="#">Sign up?</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
