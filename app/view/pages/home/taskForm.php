<form class="task-form" id="frmTarefa" method="POST">

    <label for="nome">Task Name</label>
    <input
        required
        class="form-control dark-input"
        type="text" id="nome"
        name="nome"
        placeholder="Enter task name"
        value="<?php echo (isset($dados["tarefa"]) ? $dados["tarefa"]->getNome_tarefa() : ''); ?>"
    >

    <div style="display: none" id="formDiv">
        <label for="descricao">Task Description</label>
        <textarea class="form-control dark-input" id="descricao" name="descricao" placeholder="Enter task description"><?php echo (isset($dados["tarefa"]) ? $dados["tarefa"]->getDescricao() : ''); ?></textarea>

        <div class="last-row" >
            <fieldset class="element" >
                <label>Task Difficulty</label>
                <div class="radios" >
                    <input title="easy" class="radio-square green" name="dificuldade" type="radio" value="1" checked/>
                    <input title="medium" class="radio-square yellow" name="dificuldade" type="radio" value="2"/>
                    <input title="hard" class="radio-square red" name="dificuldade" type="radio" value="3"/>
                </div>
            </fieldset>
            <fieldset class="element" >
                <label>Task Priority</label>
                <div class="radios" >
                    <input title="low" class="radio-square green" name="prioridade" type="radio" value="1" checked/>
                    <input title="medium" class="radio-square yellow" name="prioridade" type="radio" value="2"/>
                    <input title="high" class="radio-square red" name="prioridade" type="radio" value="3"/>
                </div>
            </fieldset>

            <fieldset class="points-holder" >
                <label for="valor_pontos">Task Points</label>
                <input
                    class="dark-input"
                    type="number" id="valor_pontos"
                    name="valor_pontos"
                    value="0"
                />
            </fieldset>
        </div>

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

    <input id="idUsuario" name="idUsuario" type="hidden" value="<?php echo $_SESSION[SESSAO_USUARIO_ID]; ?>" />

    <div class="btn-wrapper">
        <div class="showMoreBtn btn btn-success">
            <p id="showMoreText">Show more <i class="fas fa-chevron-down arrowIcon task-icon"></i></p>
        </div>

        <button type="submit" class="btn btn-success" id="submitTaskButton">Add Task</button>
    </div>


    <div class="col-6 p-0">
        <?php include_once(__DIR__ . "/../../include/msg.php") ?>
    </div>
</form>

