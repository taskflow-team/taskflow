// Esse arquivo armazena todo o código que diz respeito a
// Buscar tarefas no banco de dados e filtra-las

import notificate from "../notification.js";
import formatDate from '../formatDate.js';
import { completeTask, deleteTask } from "./taskFunctions.js";

const completedBtn = document.querySelector('#completedTasks');
const incompletedBtn = document.querySelector('#incompletedTasks');
const subFilter = document.querySelector('#subFilter');
const searchBtn = document.querySelector('#searchBtn');

// Função para obter a lista de tarefas atualizada do servidor
async function fetchTaskList() {
    let dataPrioritySelector = document.querySelector('#subFilter');
    let selectedRule = dataPrioritySelector.selectedIndex;

    const ruleObject = selectedRule === 1 ? { rule: 'priority' } : { rule: '' };

    const reqConfigs = {
        method: "POST",
        headers: {
            'Content-type': 'application/json'
        },
        body: JSON.stringify(ruleObject)
    };

    try {
        const response = await fetch('TarefaController.php?action=list', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        updateTaskList(responseData.data);

    } catch (error) {
        notificate('error', 'Error', error);
    }
}

// Chama a função fetchTaskList para carregar a lista de tarefas inicial na carga da página
fetchTaskList();

// Filtra as tarefas por data ou prioridade
subFilter.options[0].addEventListener('click', fetchTaskList);
subFilter.options[1].addEventListener('click', fetchTaskList);

function updateTaskList(tasks) {
    // Limpa a lista de tarefas existente
    $("#taskList").empty();

    // Mapeia os valores de dificuldade
    const difficultyMap = {
        1: 'Easy',
        2: 'Medium',
        3: 'Hard'
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

function handleTasksVisibility(element) {
    const tasks = document.querySelectorAll('.task');
    const target = element.target;
    target.classList.toggle('button-active');

    if(target.id === 'incompletedTasks' && target.classList.contains('button-active')){
        completedBtn.classList.remove('button-active');
        filterTasks('incompleted');
    } else if(target.id === 'completedTasks' && target.classList.contains('button-active')){
        incompletedBtn.classList.remove('button-active');
        filterTasks('completed');
    } else {
        filterTasks();
    }
}

function filterTasks(filter){
    let tasks = document.querySelectorAll('.task');

    tasks.forEach(task => {
        switch (filter) {
            case 'incompleted':
                task.style.display = task.classList == 'task checked' ? 'none' : 'block';
                break;
            case 'completed':
                task.style.display = task.classList == 'task checked' ? 'block' : 'none';
                break;
            default:
                task.style.display = 'block';
                break;
        }
    });
}

completedBtn.addEventListener('click', handleTasksVisibility);
incompletedBtn.addEventListener('click', handleTasksVisibility);

function searchByName(){
    let currentTasks = document.querySelectorAll('.task');
    let nameForSearch = document.querySelector('#taskNameSearch').value;

    if(nameForSearch == ''){
        if(nameForSearch == '' && completedBtn.classList.contains('button-active')){
            filterTasks('completed');
        } else if(nameForSearch == '' && incompletedBtn.classList.contains('button-active')){
            filterTasks('incompleted');
        } else {
            filterTasks();
        }
    } else {
        currentTasks.forEach(task => {
            let taskName = task.firstChild.children[1].children[0].innerText;

            if(taskName.includes(nameForSearch)){
                task.style.display = 'block';
            } else {
                task.style.display = 'none';
            }

        });
    }
}

searchBtn.addEventListener('click', searchByName);

export {
    fetchTaskList,
    filterTasks
}
