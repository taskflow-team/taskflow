<?php
// require_once(__DIR__ . "/../../../service/GrupoService.php");
require_once(__DIR__ . "/../../components/htmlHead/htmlHead.php");
require_once(__DIR__ . "/../../../dao/GrupoDAO.php");
require_once(__DIR__ . "/../../components/sideBar/sidebar.php");

// Instanciar o DAO de tarefas
$grupoDAO = new GrupoDAO();

// Pegando o id do usuário
$id_usuario = "(Sessão expirada)";
if (isset($_SESSION[SESSAO_USUARIO_NOME]))
{
    $id_usuario = $_SESSION[SESSAO_USUARIO_NOME];
}
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/pages/groupHome/groupHome.css">

<div class="pseudo-body">
    <div class="content-with-sidebar">
        <div class="content-main">
            <div class="navigation-bar">
                <button id="btn-lists" class="btn btn-success">Lists</button>
                <button id="btn-rewards" class="btn btn-success">Rewards</button>
            </div>

            <hr class="line" >  
            
            <div id="lists-holder" class="content-holder">
            </div>
            
            <div id="rewards-holder" class="content-holder">
            </div>
        </div>

        <div class="sidebar-right">
            <div class="usernames-holder">
            </div>
        </div>
    </div>
</div>