<?php
require_once(__DIR__ . "/../../../service/GrupoService.php");
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/GrupoDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");

// Instanciar o DAO de tarefas
$grupoDAO = new GrupoDAO();

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

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/group/group.css">

<!-- Exibir os grupos -->
<div class="pseudo-body">
    <div class="buttons-group">
        <h2 class="your-groups" >Your Groups</h2>
        <button id="btn-create-group" class="btn btn-success" >Create a Group +</button>
        <button id="btn-join-group" class="btn btn-success" >Join a Group</button>
    </div>

    <div class="groups-holder">

    </div>
</div>

<script>
    const BASE_URL = '<?= BASEURL; ?>';
</script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/groupsModal.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/groupsFunctions.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/groupsFilters.js"></script>
