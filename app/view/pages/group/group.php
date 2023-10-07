<?php
// require_once(__DIR__ . "/../../../service/GrupoService.php");
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/GrupoDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");

// Instanciar o DAO de tarefas
$grupoDAO = new GrupoDAO();

// Pegando o id do usuário
$id_usuario = "(Sessão expirada)";
if (isset($_SESSION[SESSAO_USUARIO_NOME]))
{
    $id_usuario = $_SESSION[SESSAO_USUARIO_NOME];
}
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/group/group.css">

<!-- Exibir os grupos -->
<div class="pseudo-body">
    <div class="buttons-group">
        <button id="btn-create-group">Criar grupo</button>
        <button id="btn-join-group">Entrar em um grupo</button>
    </div>

    <div class="groups-holder">

    </div>  
</div>

<script type="module" src="<?= BASEURL; ?>/view/js/groups/groupsModal.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/groupsFunctions.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/groupsFilters.js"></script>