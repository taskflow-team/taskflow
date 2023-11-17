<?php
// require_once(__DIR__ . "/../../../service/GrupoService.php");
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/GrupoDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");

// Instanciar o DAO de tarefas
$grupoDAO = new GrupoDAO();

$groupId = isset($_GET['groupId']) ? $_GET['groupId'] : null;
$groupName = isset($_GET['groupName']) ? $_GET['groupName'] : null;
$isAdmin = isset($_GET['isAdmin']) ? $_GET['isAdmin'] : null;

// Pegando o id do usuário
session_status();
if (session_status() !== PHP_SESSION_ACTIVE)
{
    session_start();
}

$id_usuario = "(Sessão expirada)";
if (isset($_SESSION[SESSAO_USUARIO_NOME]))
{
    $id_usuario = $_SESSION[SESSAO_USUARIO_NOME];
}
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/groupHome/groupHome.css">
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/css/lists.css">
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/css/rewards.css">

<div class="pseudo-body">
    <h2 class="group-name"><?= $groupName ?></h2>

    <div class="content-with-sidebar">
        <div class="content-main">
            <div class="navigation-bar">
                <button id="btn-lists" class="btn btn-success">Lists</button>
                <button id="btn-rewards" class="btn btn-success">Rewards</button>
            </div>

            <hr class="line" >  
            
            <div id="lists-holder" class="lists-holder">
            </div>

            <div id="rewards-holder" class="rewards-holder">
                <button id="addRewardBtn" class="btn btn-success">Add Reward</button>

                <div class="rewards-filters">
                    <button class="btn-filter-available btn btn-success active">Available</button>
                    <button class="btn-filter-unavalible btn btn-success">Unavalible</button>
                </div>
            </div>
        </div>

        <div class="sidebar-right">
            <div class="emeralds-section top-button">
                <img src="<?= BASEURL . '/view/assets/icons/emerald.png'?>" alt="Emerald icon">
                <span id="emeralds-holder"></span>
                <strong>Emeralds</strong>
            </div>
            <div class="usernames-holder">
            </div>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?= BASEURL; ?>';
    const GROUP_ID = '<?= $groupId; ?>';
    const GROUP_NAME = '<?= $groupName; ?>';
    const IS_ADMIN = '<?= $isAdmin; ?>';

    document.getElementById('btn-lists').addEventListener('click', function() {
        document.getElementById('lists-holder').style.display = 'grid';
        document.getElementById('rewards-holder').style.display = 'none';
    });

    document.getElementById('btn-rewards').addEventListener('click', function() {
        document.getElementById('lists-holder').style.display = 'none';
        document.getElementById('rewards-holder').style.display = 'block';
    });
</script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/lists/groupListsFilter.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/lists/groupListsFunctions.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/lists/groupListsModal.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/rewards/groupRewardsFilters.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/rewards/groupRewardsFunctions.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/rewards/groupRewardsModal.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/users/groupUsersFilter.js"></script>
