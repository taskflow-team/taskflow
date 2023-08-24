<?php
require_once(__DIR__ . "/../../../service/TarefaService.php");
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/TarefaDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");

// Instanciar o DAO de tarefas
$tarefaDAO = new TarefaDAO();

// Pegando o id da lista
$listId = isset($_GET['listId']) ? $_GET['listId'] : null;

// Pegando o id do usuário
$id_usuario = $_SESSION[SESSAO_USUARIO_ID];
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/components/taskForm/taskForm.css">
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/components/editModal/editModal.css">
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/css/task.css">

<!-- Exibir as tarefas -->
<div class="pseudo-body">
    <?php
        // Form para adicionar tarefas
        require_once(__DIR__ . "/../../components/taskForm/taskForm.php");
        require_once(__DIR__ . "/../../components/editModal/editModal.php");
    ?>

    <div class="filter-section" >
        <button class="filterCompletedTasks button-active" id="incompletedTasks">Incompleted Tasks</button>
        <button class="filterCompletedTasks" id="completedTasks">Completed Tasks</button>

        <select name="subFilter" id="subFilter">
            <option value="date" selected>Date</option>
            <option value="priority">Priority</option>
            <option value="difficulty">Difficulty</option>
        </select>

        <input type="text" id="taskNameSearch" class="dark-input" placeholder="Search task by name" >
        <button class="btn success" id="searchBtn" ><strong>Search</strong></button>
    </div>

    <ul id="taskList" class="task-list"></ul>
</div>

<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Incluir formAsync.js -->
<script>
    const BASE_URL = '<?= BASEURL; ?>';
    const LIST_ID = '<?= $listId; ?>'; 
</script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/taskFunctions.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/taskForm.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/taskModal.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/tasksFilter.js"></script>