<?php
#Objetivo: sidebar padrão para todas as páginas
require_once(__DIR__ . "/../../../controller/AcessoController.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/css/sidebar.css">

<div class="sidebar">
    <img class="logo" src="../view/assets/img/logo.png" alt="Logo">
    <nav>
        <a href="<?= BASEURL . '/controller/HomeController.php?action=home'?>">
            <img class="icon" src="<?= BASEURL . '/view/assets/icons/home.png'?>" alt="home icon">
            Home
        </a>
        <a href="#">
            <img class="icon" src="<?= BASEURL . '/view/assets/icons/groups.png'?>" alt="groups icon">
            Groups
        </a>
        <a href="<?= BASEURL . '/controller/UsuarioController.php?action=showProfile'?>">
            <img class="icon" src="<?= BASEURL . '/view/assets/icons/profile.png'?>" alt="profile icon">
            Profile
        </a>
    </nav>
    <a class="logout-btn" href="<?= LOGOUT_PAGE ?>">
        <img class="icon" src="<?= BASEURL . '/view/assets/icons/logout.png'?>" alt="logout icon">
        Logout
    </a>
</div>
