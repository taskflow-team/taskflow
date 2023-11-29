<?php
require_once(__DIR__ . "/../../../service/TarefaService.php");
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/TarefaDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");

// Instanciar o DAO de tarefas
$tarefaDAO = new TarefaDAO();

// Pegando o id da lista
$listId = isset($_GET['listId']) ? $_GET['listId'] : null;
$listName = isset($_GET['listName']) ? $_GET['listName'] : null;
$groupId = isset($_GET['groupId']) ? $_GET['groupId'] : null;
$isAdmin = isset($_GET['isAdmin']) ? $_GET['isAdmin'] : null;

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

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/components/taskForm/taskForm.css">
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/components/editModal/editModal.css">
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/css/task.css">

<!-- Exibir as tarefas -->
<div class="pseudo-body">
    <h2 class="list-title" ><?= $listName ?></h2>

    <?php
        // Form para adicionar tarefas
        if($isAdmin == 1)
        {
            require_once(__DIR__ . "/../../components/taskForm/taskForm.php");
            require_once(__DIR__ . "/../../components/editModal/editModal.php");
        }
    ?>

    <div id="userData">

    </div>

    <div class="filter-section" >
        <button class="filterCompletedTasks button-active" id="incompletedTasks">Incompleted Tasks</button>
        <button class="filterCompletedTasks" id="completedTasks">Completed Tasks</button>

        <select name="subFilter" id="subFilter">
            <option value="date" selected>Date</option>
            <option value="priority">Priority</option>
            <option value="difficulty">Difficulty</option>
        </select>

        <input type="text" id="taskNameSearch" class="dark-input" placeholder="Search task by name" >
    </div>

    <ul id="taskList" class="task-list"></ul>
</div>

<input id="idUsuario" name="idUsuario" type="hidden" value="<?php echo $_SESSION[SESSAO_USUARIO_ID]; ?>" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const BASE_URL = '<?= BASEURL; ?>';
    const LIST_ID = '<?= $listId; ?>';
    const GROUP_ID = '<?= $groupId; ?>';
    const IS_ADMIN = '<?= $isAdmin; ?>';
</script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/taskFunctions.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/taskForm.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/taskModal.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/tasksFilter.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/tasks/tasksFilter.js"></script>
