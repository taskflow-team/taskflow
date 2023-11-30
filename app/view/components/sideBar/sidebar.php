<?php
require_once(__DIR__ . "/../../../controller/AcessoController.php");

// Pegando o id do usuário
session_status();
if (session_status() !== PHP_SESSION_ACTIVE)
{
    session_start();
}

// Pegando o id do usuário
$id_usuario = "(Sessão expirada)";
if (isset($_SESSION[SESSAO_USUARIO_ID]))
{
    $id_usuario = $_SESSION[SESSAO_USUARIO_ID];
}
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/components/sideBar/sidebar.css">

<div class="sidebar">
    <h1 class="app-name" >TaskFlow</h1>
    <nav>
        <a href="<?= BASEURL . '/controller/HomeController.php?action=home'?>">
            <img class="icon" src="<?= BASEURL . '/view/assets/icons/home.png'?>" alt="home icon">
            Home
        </a>
        <a href="<?= BASEURL . '/controller/GrupoController.php?action=create'?>">
            <img class="icon" src="<?= BASEURL . '/view/assets/icons/groups.png'?>" alt="groups icon">
            Groups
        </a>
        <a href="<?= BASEURL . '/controller/NotificacaoController.php?action=create'?>">
            <img class="icon" src="<?= BASEURL . '/view/assets/icons/notification.png'?>" alt="notification icon">
            Notifications <span id="unreadNotificationsCount"></span>
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

<input id="idUsuario" name="idUsuario" type="hidden" value="<?php echo $_SESSION[SESSAO_USUARIO_ID]; ?>" />

<script type="module" src="<?= BASEURL; ?>/view/js/notifications/notificationsSidebar.js"></script>