import notificate from '../notification.js';
import { filterTasks } from './tasksFilter.js';
import formatDate from '../formatDate.js';

const taskForm = document.querySelector('#frmTarefa');
const completedBtn = document.querySelector('#completedTasks');
const incompletedBtn = document.querySelector('#incompletedTasks');

// Função para obter a lista de tarefas atualizada do servidor
function fetchTaskList() {
    $.ajax({
        type: "GET",
        url: "/taskflow/app/controller/TarefaController.php?action=list",
        success: function (response) {
            if (response.message === "Success") {
                // Atualiza a lista de tarefas na página
                updateTaskList(response.data);
            } else {
                notificate('error', 'Error', 'There was an error while updating the task list');
            }
        },
        error: function (xhr, status, error) {
            notificate('error', 'Error', error);
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
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        // Obtém a lista de tarefas atualizada após criar a tarefa
        fetchTaskList();
    } catch (error) {
        notificate('error', 'Error', error);
    }
}

taskForm.addEventListener('submit', createTask);

function updateTaskList(tasks) {
    // Limpa a lista de tarefas existente
    $("#taskList").empty();

    // Mapeia os valores de dificuldade
    const difficultyMap = {
        easy: 'Easy',
        medium: 'Medium',
        hard: 'Hard'
    };

    // Mapeia os valores de prioridade
    const priorityMap = {
        1: 'Low',
        2: 'Medium',
        3: 'High'
    };

    // Adiciona cada tarefa à lista de tarefas
    tasks.forEach(function (task) {
        // Formata a data de criação da tarefa
        const formattedDate = formatDate(task.data_criacao);
        const taskCompleted = task.concluida == 1 ? 'checked' : '';

        $("#taskList").append(
            "<li class='task "+taskCompleted+"' id='" + task.id_tarefa + "'>" +
                // Conteúdo principal
                "<div class='top-content' >" +

                "<input type='checkbox' class='completedBtn' data-id='" + task.id_tarefa + "' " + taskCompleted + ">" +

                    "<div>" +
                        "<p class='task-name'><strong>" + task.nome_tarefa + "</strong></p>" +
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
                    "<p>Creation Date: " + formattedDate + "</p>" +
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

    // Anexa um evento de clique ao botão de conclusão de tarefa
    $(".completedBtn").change(completeTask);

    // Anexa um evento de clique ao botão de exclusão de tarefa
    $(".deleteBtn").click(deleteTask);

    if(completedBtn.classList.contains('button-active')){
        filterTasks('completed', tasks);
    } else if(incompletedBtn.classList.contains('button-active')){
        filterTasks('incompleted', tasks);
    } else {
        filterTasks('', tasks);
    }
}

async function completeTask(element) {
    const taskId = element.target.parentElement.parentElement.id; // checkbox is grandchild of the task

    const rawFormContent = new FormData(taskForm);
    const formData = Object.fromEntries(rawFormContent);
    taskForm.reset();

    const taskCompleted = $(this).prop("checked");

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                taskId: taskId,
                formData: formData,
                taskCompleted: taskCompleted
            })
        };

        const response = await fetch('TarefaController.php?action=edit', reqConfigs);

        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        // Obtém a lista de tarefas atualizada após completar a tarefa
        fetchTaskList();
    } catch (error) {
        notificate('error', 'Error', error);
    }
}

function toggleShowMore(taskId) {
    const arrowIcon = $(`[data-task-id="${taskId}"] i`);
    const moreInfoDiv = $(`#moreInfo_${taskId}`);

    // Fazer o div de informações adicionais aparecer ou desaparecer
    moreInfoDiv.toggle();

    // Faz o ícone da seta girar 180 graus
    arrowIcon.toggleClass("rotated");
}

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
            notificate('error', 'Error', 'The request to the server has failed');
        } else {
            // Realiza um fetch da lista de tarefas atualizada após excluir a tarefa
            fetchTaskList();
        }
    } catch (error) {
        notificate('error', 'Error', error);
    }
}
