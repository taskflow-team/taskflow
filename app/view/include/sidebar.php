<?php
#Objetivo: sidebar padrão para todas as páginas
require_once(__DIR__ . "/../../controller/AcessoController.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/include/sidebar.css">

<div class="sidebar">
    <img src="../view/img/logo.png" alt="Logo">
    <nav>
        <a href="<?= BASEURL . '/controller/HomeController.php?action=home'?>">Home</a>
        <a href="#">Groups</a>
        <a href="#">Profile</a>
    </nav>
    <a class="logout-btn" href="<?= LOGOUT_PAGE ?>">Logout</a>
</div>