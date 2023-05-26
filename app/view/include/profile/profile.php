<?php
#Nome do arquivo: home/index.php
#Objetivo: interface com a página inicial do sistema

require_once(__DIR__ . "/../header.php"); //meta links and botstrap
require_once(__DIR__ . "/../sidebar.php");
?>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/view/include/global.css">

<div class="pseudo-body">
    <h1><?php print_r($dados["usuario"]) ; ?></h1>
</div>