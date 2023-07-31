// Form view
const showMore = document.querySelector("#showMore");
const formDiv = document.querySelector("#formDiv");
const taskForm = document.querySelector('#frmTarefa');

function showForm() {
    if (formDiv.style.display === 'none') {
        formDiv.style.display = 'block';
        showMore.innerText = 'Show less';
    } else {
        formDiv.style.display = 'none';
        showMore.innerText = 'Show more';
    }
}

showMore.addEventListener("click", showForm);

// Task functions
function toggleShowMore(taskId) {
    const arrowIcon = $(`[data-task-id="${taskId}"] i`);
    const moreInfoDiv = $(`#moreInfo_${taskId}`);
    const task = $(`#task_${taskId}`);

    // Fazer o div de informações adicionais aparecer ou desaparecer
    moreInfoDiv.toggle();

    // Calcula a margem inferior do elemento da tarefa
    // const margin = moreInfoDiv.is(":visible") ? moreInfoDiv.outerHeight() : 0;
    // task.css("margin-bottom", margin + "px");
    task.css("height: fit-content;");

    // Faz o ícone da seta girar 180 graus
    arrowIcon.toggleClass("rotated");
}



function updateTaskList(tasks) {
    // Limpa a lista de tarefas existente
    $("#taskList").empty();

    const difficultyMap = {
        easy: 'Easy',
        medium: 'Medium',
        hard: 'Hard'
    };

    const priorityMap = {
        1: 'Low',
        2: 'Medium',
        3: 'High'
    };

    // Adiciona cada tarefa à lista de tarefas
    tasks.forEach(function (task) {
        $("#taskList").append(
            "<li class='task' id='task_" + task.id_tarefa + "'>" +
                // Conteúdo principal
                "<div class='top-content' >" +
                    "<div>" +
                        "<p><strong>" + task.nome_tarefa + "</strong></p>" +
                        "<p>" + task.descricao_tarefa + "</p>" +
                    "</div>" +

                    // Icones
                    "<i class='fa-regular fa-pen-to-square task-icon editBtn'></i>" +
                    "<i class='fa-solid fa-trash task-icon deleteBtn' data-id='" + task.id_tarefa + "'></i>" +

                    // Adiciona botão para exibir mais informações sobre a tarefa
                    "<div class='showMoreBtn' data-task-id='" + task.id_tarefa + "'>" +
                        "<i class='fas fa-chevron-down arrowIcon task-icon'></i>" +
                    "</div>" +
                "</div>" +

                // Div escondida
                "<div id='moreInfo_" + task.id_tarefa + "' class='moreInfoDiv' style='display: none;'>" +
                    "<p>Points: " + task.valor_pontos + "</p>" +
                    "<p>Priority: " + priorityMap[task.prioridade] + "</p>" +
                    "<p>Difficulty: " + difficultyMap[task.dificuldade] + "</p>" +
                "</div>" +

                // Etiqueta de dificuldade
                "<div class='difficulty " + task.dificuldade + "'></div>" +
            "</li>"
        );
    });

    // Verifica se a lista de tarefas está vazia e exibe uma mensagem apropriada
    if (tasks.length === 0) {
        $("#taskList").append("<p>No pending tasks</p>");
    }

    // Anexa um evento de clique ao botão de exclusão de tarefa
    $(".deleteBtn").click(deleteTask);
}

// Anexa um evento de clique ao botão de exibição de mais informações
$(document).on("click", ".showMoreBtn", function () {
    const taskId = $(this).data("task-id");
    toggleShowMore(taskId);
});

async function deleteTask() {
    const taskId = $(this).data("id");

    try {
        const response = await fetch(`TarefaController.php?action=delete&id=${taskId}`, {
            method: "GET",
        });

        if (!response.ok) {
            console.log("A requisição ao servidor falhou");
        } else {
            const responseData = await response.json();
            console.log(responseData);

            // Realiza um fetch da lista de tarefas atualizada após excluir a tarefa
            fetchTaskList();
        }
    } catch (error) {
        console.log(`Ocorreu um erro durante a requisição: ${error}`);
    }
}

// Função para obter a lista de tarefas atualizada do servidor
function fetchTaskList() {
    $.ajax({
        type: "GET",
        url: "/taskflow/app/controller/TarefaController.php?action=list",
        dataType: "json",
        success: function (response) {
            if (response.message === "Success") {
                // Atualiza a lista de tarefas na página
                updateTaskList(response.data);
            } else {
                console.error("Ocorreu um erro ao obter a lista de tarefas.");
            }
        },
        error: function (xhr, status, error) {
            console.error("Erro: " + status + " - " + error);
        }
    });
}

// Chama a função fetchTaskList para carregar a lista de tarefas inicial na carga da página
fetchTaskList();

async function createTask(event) {
    event.preventDefault();

    const userID = Number(document.querySelector('#idUsuario').value);

    const rawFormContent = new FormData(taskForm);
    const formData = Object.fromEntries(rawFormContent);
    taskForm.reset();

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                formData: formData,
                userID: userID
            })
        };

        const response = await fetch('TarefaController.php?action=save', reqConfigs);

        if (!response.ok) {
            console.log('A requisição ao servidor falhou');
        }

        const responseData = await response.json();
        console.log(responseData);

        // Obtém a lista de tarefas atualizada após criar a tarefa
        fetchTaskList();
    } catch (error) {
        console.log(`Ocorreu um erro durante a requisição: ${error}`);
    }
}

taskForm.addEventListener('submit', createTask);
