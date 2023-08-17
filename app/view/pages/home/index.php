<?php
require_once(__DIR__ . "/../../../service/TarefaService.php");
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/TarefaDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");

// Instanciar o DAO de tarefas
$tarefaDAO = new TarefaDAO();

// Pegando o id do usuário
$id_usuario = $_SESSION[SESSAO_USUARIO_ID];
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/home/listCard.css">

<!-- Exibir as tarefas -->
<div class="pseudo-body">
    <!-- <div class="list-card create-list">
        <i class="fa-regular fa-plus"></i>
    </div> -->



</div>

<script type="module" src="../view/js/lists/listFunctions.js"></script>
<script type="module" src="../view/js/lists/listFilter.js"></script>