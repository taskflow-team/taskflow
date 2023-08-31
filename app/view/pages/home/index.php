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
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/css/rewards.css">
<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/home/index.css">

<!-- Exibir as tarefas -->
<div class="pseudo-body">
    <div class="top-content">
        <h2>Personal Lists</h2>

        <div class="top-content-buttons" >
            <div class="emeralds-section top-button">
                <img src="<?= BASEURL . '/view/assets/icons/emerald.png'?>" alt="Emerald icon">
                <span id="emeralds-holder" ></span>
                <strong>Emeralds</strong>
            </div>

            <div class="rewards top-button" id="rewardsBtn" >
                <i class="fa-solid fa-gift"></i>
                <strong>Rewards</strong>
            </div>
        </div>
    </div>

    <div class="lists-holder">

    </div>
</div>

<div class="rewards-bar rewards-hidden" >
    <h3>Rewards</h3>

    <div class="rewards-holder" >
    <div class="reward-card" >
            <div class="default-content" >
                <span>Day off</span>
                <div class="cost" >
                    770
                    <img src="<?= BASEURL . '/view/assets/icons/emerald.png'?>" alt="Emerald icon">
                </div>
            </div>
            <div class="rewards-functions functions-hidden" >

            </div>
        </div>

        <div class="reward-card" >
            <div class="default-content" >
                <span>Ice cream</span>
                <div class="cost" >
                    230
                    <img src="<?= BASEURL . '/view/assets/icons/emerald.png'?>" alt="Emerald icon">
                </div>
            </div>
            <div class="rewards-functions functions-show" >
                <button id="claimBtn" >Claim</button>
                <button id="deleteBtn" >Delete</button>
            </div>
        </div>
    </div>

</div>

<input id="idUsuario" name="idUsuario" type="hidden" value="<?php echo $_SESSION[SESSAO_USUARIO_ID]; ?>" />

<script type="module" src="../view/js/lists/listFunctions.js"></script>
<script type="module" src="../view/js/lists/listFilter.js"></script>
<script type="module" src="../view/js/rewards/rewards.js"></script>
