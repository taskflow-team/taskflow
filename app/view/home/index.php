<?php
#Nome do arquivo: home/index.php
#Objetivo: interface com a página inicial do sistema

require_once(__DIR__ . "/../include/header.php");
require_once(__DIR__ . "/../include/menu.php");
?>

<h3 class="text-center">Projeto integrador do IFPR</h3>

<div class="container">
    <div class="row">
        <div class="col-12">
            <img class="mx-auto d-block" src="<?= BASEURL . "/view/img/ifpr_foz.jpg" ?>" />
        </div>
    </div>
</div>

<?php  
require_once(__DIR__ . "/../include/footer.php");
?>
