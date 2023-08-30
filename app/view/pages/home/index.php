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

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/css/lists.css">

<!-- Exibir as tarefas -->
<div class="pseudo-body">
    <div class="top-content">
        <h2>Your Personal Lists</h2>
        <div class="emeralds-section">
            <img src="<?= BASEURL . '/view/assets/icons/emerald.png'?>" alt="Emerald icon">
            <span id="emeralds-holder" ></span>
            <strong>Emeralds</strong>
        </div>
    </div>
    <div class="lists-holder"></div>
</div>

<input id="idUsuario" name="idUsuario" type="hidden" value="<?php echo $_SESSION[SESSAO_USUARIO_ID]; ?>" />

<script type="module" src="../view/js/lists/listFunctions.js"></script>
<script type="module" src="../view/js/lists/listFilter.js"></script>
