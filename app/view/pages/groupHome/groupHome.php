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
if (isset($_SESSION[SESSAO_USUARIO_ID]))
{
    $id_usuario = $_SESSION[SESSAO_USUARIO_ID];
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
                <button id="btn-members" class="btn btn-success">Members</button>

                <div class="group-emeralds">
                    <img src="<?= BASEURL . '/view/assets/icons/emerald.png'?>" alt="Emerald icon">
                    <span id="emeralds-holder"></span>
                    <strong>Emeralds</strong>
                </div>
            </div>

            <hr class="line" >

            <div id="lists-holder" class="lists-holder">
            </div>

            <div id="rewards-controls-holder">
                <div class="rewards-filters">
                    <button id="addRewardBtn" class="btn btn-success">Add Reward</button>
                    <button class="btn-filter-available btn btn-success active">Available</button>
                    <button class="btn-filter-unavailable btn btn-success">Unavailable</button>
                </div>
            </div>

            <div id="members" class="members">
                <table class="members-table">
                </table>
            </div>

            <div id="rewards-holder" class="rewards-holder">
            </div>
        </div>
    </div>
</div>

<input id="idUsuario" name="idUsuario" type="hidden" value="<?php echo $_SESSION[SESSAO_USUARIO_ID]; ?>" />

<script>
    const BASE_URL = '<?= BASEURL; ?>';
    const GROUP_ID = '<?= $groupId; ?>';
    const GROUP_NAME = '<?= $groupName; ?>';
    const IS_ADMIN = '<?= $isAdmin; ?>';
    const USER = '<?= $id_usuario; ?>';

    document.getElementById('btn-lists').addEventListener('click', function() {
        document.getElementById('lists-holder').style.display = 'grid';
        document.getElementById('rewards-holder').style.display = 'none';
        document.getElementById('members').style.display = 'none';
        document.getElementById('rewards-controls-holder').style.display = 'none';
    });

    document.getElementById('btn-rewards').addEventListener('click', function() {
        document.getElementById('lists-holder').style.display = 'none';
        document.getElementById('members').style.display = 'none';
        document.getElementById('rewards-holder').style.display = 'block';
        document.getElementById('rewards-controls-holder').style.display = 'block';
    });

    document.getElementById('btn-members').addEventListener('click', function() {
        document.getElementById('members').style.display = 'block';
        document.getElementById('lists-holder').style.display = 'none';
        document.getElementById('rewards-holder').style.display = 'none';
        document.getElementById('rewards-controls-holder').style.display = 'none';
    });
</script>

<script type="module" src="<?= BASEURL; ?>/view/js/groups/lists/groupListsFilter.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/lists/groupListsFunctions.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/lists/groupListsModal.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/rewards/groupRewardsFilters.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/rewards/groupRewardsFunctions.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/rewards/groupRewardsModal.js"></script>
<script type="module" src="<?= BASEURL; ?>/view/js/groups/users/groupUsersFilter.js"></script>
