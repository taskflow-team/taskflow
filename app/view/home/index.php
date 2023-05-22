<?php
#Nome do arquivo: home/index.php
#Objetivo: interface com a página inicial do sistema

require_once(__DIR__ . "/../include/header.php"); //meta links and botstrap
require_once(__DIR__ . "/../include/sidebar.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/home/index.css">

<div class="pseudo-body">
    <form action="">
        <input type="text" placeholder="Write your task here">
        <button type="submit" class="btn btn-success">Add task</button>
    </form>

    <h3>Unfinished Tasks</h3>

    <ul class="tasks">

    </ul>
</div>