<?php
require_once(__DIR__ . "/../../service/TarefaService.php");
require_once(__DIR__ . "/../include/header.php");
require_once(__DIR__ . "/../../dao/TarefaDAO.php");
require_once(__DIR__ . "/../include/sidebar.php");

// Instanciar o DAO de tarefas
$tarefaDAO = new TarefaDAO();

// Obter todas as tarefas do banco de dados
$tarefas = $tarefaDAO->listTarefas();
?>

<link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/view/home/index.css">

<!-- Exibir as tarefas -->
<div class="pseudo-body">
    <?php 
        // Form para adicionar tarefas
        require_once(__DIR__ . "/form.php");
    ?>
    <h2>Tarefas Pendentes</h2>
    <ul class="task-list">
        <?php
            if (!empty($tarefas)) {
                foreach ($tarefas as $tarefa) {
                    echo "<li class='task' >";
                    echo "<div>";
                        echo "<p><strong>". $tarefa->getNome_tarefa() . "</strong></p>";
                        echo "<p>" . $tarefa->getDescricao_tarefa() . "</p>";
                    echo "</div>";
                    // echo "<p><strong>Prioridade:</strong> " . $tarefa->getPrioridade() . "</p>";
                    // echo "<p><strong>Pontos:</strong> " . $tarefa->getValor_pontos() . "</p>";
                    echo "<a href=\"../controller/TarefaController.php?action=delete&id=" . $tarefa->getId_tarefa() . "\">Delete</a>";
                    echo "<div class='difficulty ". $tarefa->getDificuldade()  ."' />";
                    echo "</li>";
                }
            } else {
                echo "<p>Não há tarefas para exibir.</p>";
            }
        ?>
    </ul>
</div>