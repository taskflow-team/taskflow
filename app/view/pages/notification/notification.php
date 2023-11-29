<?php
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/NotificacaoDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");
require_once(__DIR__ . "/../../../controller/AcessoController.php");

$notificacaoDAO = new NotificacaoDAO();

session_status();
if (session_status() !== PHP_SESSION_ACTIVE)
{
    session_start();
}

$id_usuario = "(Sessão expirada)";
if (isset($_SESSION[SESSAO_USUARIO_ID]))
{
    $id_usuario = $_SESSION[SESSAO_USUARIO_ID];
}
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/notification/notification.css">

<div class="pseudo-body">
    <h2 class="your-notifications" >Notifications</h2>
    <div class="notifications-holder">
    </div>
</div>

<script>
    const USER = '<?= $id_usuario; ?>';
</script>
<script type="module" src="<?= BASEURL; ?>/view/js/notifications/notificationsFunctions.js"></script>
