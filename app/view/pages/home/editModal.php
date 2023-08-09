<div id="taskModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Task</h2>
        
        <form class="task-edit-form" id="frmEditTarefa" method="POST">
            <input required class="form-control dark-input nameEditTask" type="text" id="nameEditTask" name="nameEditTask" placeholder="Enter task name" value="">

            <textarea class="form-control dark-input" id="taskDescription" name="taskDescription" placeholder="Enter task description"></textarea>

            <div class="last-row" >
                <fieldset class="element">
                    <label>Task Difficulty</label>
                    <div class="radios">
                        <input title="easy" class="radio-square green" id="difficulty1" name="dificuldade" type="radio" value="1"/>
                        <input title="medium" class="radio-square yellow" id="difficulty2" name="dificuldade" type="radio" value="2"/>
                        <input title="hard" class="radio-square red" id="difficulty3" name="dificuldade" type="radio" value="3"/>
                    </div>
                </fieldset>
                <fieldset class="element">
                    <label>Task Priority</label>
                    <div class="radios">
                        <input title="low" class="radio-square green" id="priority1" name="prioridade" type="radio" value="1"/>
                        <input title="medium" class="radio-square yellow" id="priority2" name="prioridade" type="radio" value="2"/>
                        <input title="high" class="radio-square red" id="priority3" name="prioridade" type="radio" value="3"/>
                    </div>
                </fieldset>
                <fieldset class="points-holder">
                    <label for="valor_pontos">Task Points</label>
                    <input
                        class="dark-input"
                        type="number" id="valor_pontos"
                        name="valor_pontos"
                        value="0"
                    />
                </fieldset>
            </div>
        </form>

        <button class="btn success" id="editTaskBtn" >Edit Task</button>
    </div>

    <div class="col-6 p-0">
        <?php include_once(__DIR__ . "/../../include/msg.php") ?>
    </div>
</div>