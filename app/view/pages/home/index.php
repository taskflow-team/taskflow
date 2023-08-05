<?php
require_once(__DIR__ . "/../../../service/TarefaService.php");
require_once(__DIR__ . "/../../include/header.php");
require_once(__DIR__ . "/../../../dao/TarefaDAO.php");
require_once(__DIR__ . "/../../include/sidebar.php");

// Instanciar o DAO de tarefas
$tarefaDAO = new TarefaDAO();

// Pegando o id do usuário
$id_usuario = $_SESSION[SESSAO_USUARIO_ID];

// Obter todas as tarefas do banco de dados
$tarefas = $tarefaDAO->listTarefas($id_usuario);
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/home/taskForm.css">
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/css/task.css">

<!-- Exibir as tarefas -->
<div class="pseudo-body">
    <?php
        // Form para adicionar tarefas
        require_once(__DIR__ . "/taskForm.php");
    ?>

    <h2>Pending Tasks</h2>
    <button class="filterCompletedTasks" id="completedTasks">Completed Tasks</button>
    <button class="filterCompletedTasks" id="incompletedTasks">Incompleted Tasks</button>
    <ul id="taskList" class="task-list"></ul>
</div>

<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Incluir formAsync.js -->
<script type="module" src="../view/js/taskFunctions.js"></script>
<script type="module" src="../view/js/taskForm.js"></script>
