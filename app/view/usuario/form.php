<?php
#Nome do arquivo: usuario/list.php
#Objetivo: interface para listagem dos usuários do sistema
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/usuario/form.css">

<div class="login-image">
    <img src="../view/img/register.jpg" alt="Image">
</div>

<div class="container">
    <div class="login-form">
        <div class="login-logo">
            <img src="../view/img/logo.png" alt="Logo">
        </div>
         <h3 class="text-center">
             <?php if($dados['id'] == 0) echo "Inserir"; else echo "Alterar"; ?> 
             Usuário
        </h3>
        <form id="frmUsuario" method="POST" action="<?= BASEURL ?>/controller/UsuarioController.php?action=save">
            <div class="form-group">
                <input class="form-control" type="text" id="txtNome" name="nome"
                    maxlength="70" placeholder="Informe o nome"
                    value="<?php echo (isset($dados["usuario"]) ? $dados["usuario"]->getNome() : ''); ?>" />
            </div>

            <div class="form-group">
                <input class="form-control" type="email" id="txtEmail" name="email"
                    maxlength="15" placeholder="Informe seu email"
                    value="<?php echo (isset($dados["usuario"]) ? $dados["usuario"]->getEmail() : ''); ?>"/>
            </div>

            <div class="form-group">
                <input class="form-control" type="text" id="txtLogin" name="login"
                    maxlength="15" placeholder="Informe o login"
                    value="<?php echo (isset($dados["usuario"]) ? $dados["usuario"]->getLogin() : ''); ?>"/>
            </div>

            <div class="form-group">
                <input class="form-control" type="password" id="txtPassword" name="senha"
                    maxlength="15" placeholder="Informe a senha"
                    value="<?php echo (isset($dados["usuario"]) ? $dados["usuario"]->getSenha() : ''); ?>"/>
            </div>

            <div class="form-group">
                <input class="form-control" type="password" id="txtConfSenha" name="conf_senha"
                    maxlength="15" placeholder="Informe a confirmação da senha"
                    value="<?php echo isset($dados['confSenha']) ? $dados['confSenha'] : '';?>"/>
            </div>

            <input type="hidden" id="hddId" name="id"
                value="<?= $dados['id']; ?>" />

            <button type="submit" class="btn btn-success">Gravar</button>
            <button type="reset" class="btn btn-danger">Limpar</button>
            <div>
                <p>Don´t have an account? <a href="<?= BASEURL . '/controller/LoginController.php?action=login' ?>">Sign up?</a></p>
            </div>
        </form>
    </div>
</div>
