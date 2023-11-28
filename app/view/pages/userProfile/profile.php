<?php
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/css/global.css">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/pages/userProfile/profile.css">

<div class="pseudo-body">
    <div class="profile-holder">
        <div class="avatar-section">
            <img id="profile-picture" src="../view/assets/img/profile.png" alt="profile picture">
            <input type="file" id="profile-image-upload" accept="image/png" style="display: none;">
            <h1 id="user-name"></h1>
            <h2 id="user-login"></h2>
            <h2 id="user-nivel"></h2>
            <p id="finished-tasks"></p>
        </div>

        <form id="frmEditUsuario" method="POST" action="<?= BASEURL ?>/controller/UsuarioController.php?action=edit">
            <div class="input-group">
                <label for="user-email">Email:</label>
                <input class="dark-input" type="email" id="user-email" name="email" placeholder="Your email" readonly required />
                <button class="btn edit" id="btnEmail">Edit</button>
            </div>

            <div class="input-group">
                <label for="user-password">Password:</label>
                <input class="dark-input" type="password" id="user-password" name="senha" placeholder="Password" readonly required />
                <img src="../view/assets/icons/eye.png" alt="eye icon" class="eyeIcon">
                <button class="btn edit" id="btnPassword">Edit</button>
            </div>

            <input type="hidden" id="userId" name="id">
            <button type="submit" class="btn btn-success" id="btnSubmit">Update Profile</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="module" src="../view/js/profile.js"></script>