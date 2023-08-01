<?php
    require_once(__DIR__ . "/../../include/header.php"); //meta links and botstrap
    require_once(__DIR__ . "/../../include/sidebar.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/css/global.css">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/pages/userProfile/profile.css">

<div class="pseudo-body">
        <div class="profile-holder">
            <img src="../view/assets/img/profile.png" alt="profile picture">
            <h1 id="user-name" ></h1>
            <h2 id="user-login" ></h2>
            <h2 id="user-nivel" ></h2>
            <p  id="finished-tasks" ></p>

            <form id="frmEditUsuario" method="POST" action="<?= BASEURL ?>/controller/UsuarioController.php?action=edit">
                <label for="email">Email:</label>
                <div>
                    <input
                        class="dark-input"
                        type="email"
                        id="user-email"
                        name="email"
                        placeholder="Your email" readonly
                        required
                    />
                    <button class="btn edit" id="btnEmail">Edit</button>
                </div>

                <label for="senha">Password:</label>
                <div>
                    <input
                        class="dark-input"
                        type="password"
                        id="user-password"
                        name="senha"
                        placeholder="Password" readonly
                        required
                    />
                    <img src="../view/assets/icons/eye.png" alt="eye icon" class="eyeIcon">
                    <button class="btn edit" id="btnPassword">Edit</button>
                </div>

                <button type="submit" class="btn btn-success" id="btnSubmit">Update Profile</button>
            </form>
        </div>
</div>

<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Incluir Scripts do profile -->
<script type="module" src="../view/js/profile.js"></script>
