<?php
require_once(__DIR__ . "/../../service/TarefaService.php");
require_once(__DIR__ . "/../include/header.php");
require_once(__DIR__ . "/../../dao/TarefaDAO.php");
require_once(__DIR__ . "/../include/sidebar.php");

// Instanciar o DAO de tarefas
$tarefaDAO = new TarefaDAO();

// Pegando o id do usuário
$id_usuario = $_SESSION[SESSAO_USUARIO_ID];

// Obter todas as tarefas do banco de dados
$tarefas = $tarefaDAO->listTarefas($id_usuario);
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/home/index.css">

<!-- Exibir as tarefas -->
<div class="pseudo-body">
    <?php 
        // Form para adicionar tarefas
        require_once(__DIR__ . "/form.php");
    ?>
    
    <h2>Pending Tasks</h2>
    <ul id="taskList" class="task-list"></ul>
</div>

<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Incluir Icones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Incluir formAsync.js -->
<script src="../view/home/formAsync.js"></script>