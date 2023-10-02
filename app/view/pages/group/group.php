<?php
// require_once(__DIR__ . "/../../../service/GrupoService.php");
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/GrupoDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");

// Instanciar o DAO de tarefas
$grupoDAO = new GrupoDAO();

// Pegando o id do usuário
$id_usuario = $_SESSION[SESSAO_USUARIO_ID];
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/group/group.css">

<!-- Exibir os grupos -->
<div class="pseudo-body">
    <div class="buttons-group">
        <button id="btn-create-group">Criar grupo</button>
        <button id="btn-join-group">Entrar em um grupo</button>
    </div>
</div>

<script type="module" src="<?= BASEURL; ?>/view/js/groups/groupsModal.js"></script>