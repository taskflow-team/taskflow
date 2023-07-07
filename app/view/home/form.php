<form id="frmTarefa" method="POST" action="<?= BASEURL ?>/controller/TarefaController.php?action=save">
   
    <label for="nome">Task Name</label>
    <input class="form-control dark-input" type="text" id="nome" name="nome" placeholder="Enter task name" 
    value="<?php echo (isset($dados["tarefa"]) ? $dados["tarefa"]->getNome_tarefa() : ''); ?>"   >

    <p id="showMore" >Show more</p>

    <div style="display: none" id="formDiv">
        <label for="descricao">Task Description</label>
        <textarea class="form-control dark-input" id="descricao" name="descricao" placeholder="Enter task description"   ><?php echo (isset($dados["tarefa"]) ? $dados["tarefa"]->getDescricao() : ''); ?></textarea>
        
        <fieldset>
            <label>Task Difficulty:</label>
            <div>
                <input name="dificuldade" type="radio" value="easy" checked/>
                <label for="easy">Easy</label>
                <input name="dificuldade" type="radio" value="medium"/>
                <label for="medium">Medium</label>
                <input name="dificuldade" type="radio" value="hard"/>
                <label for="hard">Hard</label>
            </div>
        </fieldset>

        <fieldset>
            <label>Task Priority:</label>
            <div>
                <input name="prioridade" type="radio" value="1" checked/>
                <label for="low">Low</label>
                <input name="prioridade" type="radio" value="2"/>
                <label for="medium">Medium</label>
                <input name="prioridade" type="radio" value="3"/>
                <label for="high">High</label>
            </div>
        </fieldset>
        
        <label for="valor_pontos">Task Points</label>
        <input class="form-control dark-input" type="number" id="valor_pontos" name="valor_pontos" placeholder="Enter task points"
        value="<?php echo (isset($dados["tarefa"]) ? $dados["tarefa"]->getValor_Pontos() : ''); ?>" />

        <div class="col-6">
            <?php
            // Exibir erros, se houver
            if (isset($_SESSION['tarefa_erros']) && count($_SESSION['tarefa_erros']) > 0) {
                $erros = $_SESSION['tarefa_erros'];
                foreach ($erros as $erro) {
                    echo "<p class='error'>$erro</p>";
                }
            }
            ?>
        </div>
    </div>

    <button type="submit" class="btn btn-success" id="submitTaskButton">Add Task</button>
    <div class="col-6 p-0">
        <?php include_once(__DIR__ . "/../include/msg.php") ?>
    </div>
</form>

<script>
    const showMore = document.querySelector("#showMore");
    const formDiv = document.querySelector("#formDiv");

    showMore.addEventListener("click", () => {
    if (formDiv.style.display === 'none') {
        formDiv.style.display = 'block';
        showMore.innerText = 'Show less';
    } else {
        formDiv.style.display = 'none';
        showMore.innerText = 'Show more';
    }
    });
</script>