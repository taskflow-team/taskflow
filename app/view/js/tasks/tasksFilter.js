// Esse arquivo armazena todo o código que diz respeito a
// Buscar tarefas no banco de dados e filtra-las

import notificate from "../notification.js";
import formatDate from '../formatDate.js';
import { completeTask, deleteTask } from "./taskFunctions.js";
import { openEditModal } from "./taskModal.js";

const completedBtn = document.querySelector('#completedTasks');
const incompletedBtn = document.querySelector('#incompletedTasks');
const subFilter = document.querySelector('#subFilter');
const searchBtn = document.querySelector('#searchBtn');
const taskList = document.querySelector('#taskList');
const userData = document.querySelector('#userData');

// Função para obter os dados do usuário do servidor
async function fetchUserData() {
    const reqConfigs = {
        method: "GET",
        headers: {
            'Content-type': 'application/json'
        },
    };

    try {
        const response = await fetch(BASE_URL + '/controller/UsuarioController.php?action=getUserData', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        updateUserData(responseData.user);

    } catch (error) {
        notificate('error', 'Error', error);
    }
}

// Chama a função fetchUserData para carregar os dados do usuário na carga da página
fetchUserData();

function updateUserData(user) {
    // Update the user data on the page with the fetched data
    const {
        id,
        nome,
        email,
        pontos,
        nivel,
        tarefas_concluidas
    } = user;

    const userDataHtml = `
        <p><strong>User ID:</strong> ${id}</p>
        <p><strong>Points:</strong> ${pontos}</p>
    `;

    userData.innerHTML = userDataHtml;
}

// Função para obter a lista de tarefas atualizada do servidor
async function fetchTaskList() {
    let dataPrioritySelector = document.querySelector('#subFilter');
    let selectedRule = dataPrioritySelector.selectedIndex;
    let rule = '';

    if (selectedRule == 1 ) {
        rule =  'priority';
    } else if(selectedRule == 2) {
        rule = 'difficulty';
    } else {
        rule = '';
    }

    const reqConfigs = {
        method: "POST",
        headers: {
            'Content-type': 'application/json'
        },
        body: JSON.stringify({
            rule: rule,
            listID: LIST_ID
        })
    };

    try {
        const response = await fetch(BASE_URL + '/controller/TarefaController.php?action=list&listId=' + LIST_ID, reqConfigs);
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
subFilter.addEventListener('click', fetchTaskList);

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

    // Cria e adiciona cada tarefa à lista de tarefas
    tasks.forEach(function (task) {
        const {
            id_tarefa,
            concluida,
            nome_tarefa,
            descricao_tarefa,
            dificuldade,
            prioridade,
            data_criacao,
            valor_pontos,
            idtb_listas
        } = task;

        // Formata a data de criação da tarefa
        const formattedDate = formatDate(data_criacao);

        const liElement = document.createElement('li');
        liElement.className = 'task ' + (concluida == 1 ? 'checked' : '');
        liElement.id = id_tarefa;

        const topContentDiv = document.createElement('div');
        topContentDiv.className = 'top-content';

        const checkbox = document.createElement('input');
        checkbox.id = "checkbox-task"
        checkbox.type = 'checkbox';
        checkbox.className = 'completedBtn';
        checkbox.dataset.id = id_tarefa;
        checkbox.checked = concluida == 1;
        checkbox.addEventListener('click', completeTask);

        const contentDiv = document.createElement('div');
        const taskName = document.createElement('p');
        taskName.className = 'task-name';
        taskName.innerHTML = '<strong>' + nome_tarefa + '</strong>';
        const taskDescription = document.createElement('p');
        taskDescription.textContent = descricao_tarefa;

        const editIcon = document.createElement('i');
        editIcon.className = 'fa-regular fa-pen-to-square task-icon editBtn';
        editIcon.dataset.taskId = id_tarefa;
        editIcon.addEventListener('click', () => openEditModal(
            id_tarefa,
            nome_tarefa,
            descricao_tarefa,
            parseInt(dificuldade),
            parseInt(prioridade),
            valor_pontos,
            idtb_listas
        ));

        const deleteIcon = document.createElement('i');
        deleteIcon.className = 'fa-solid fa-trash task-icon deleteBtn';
        deleteIcon.dataset.id = id_tarefa;
        deleteIcon.addEventListener('click', deleteTask);

        const showMoreDiv = document.createElement('div');
        showMoreDiv.className = 'showMoreBtn';
        showMoreDiv.dataset.taskId = id_tarefa;

        const arrowIcon = document.createElement('i');
        arrowIcon.className = 'fas fa-chevron-down arrowIcon task-icon';

        showMoreDiv.appendChild(arrowIcon);

        contentDiv.appendChild(taskName);
        contentDiv.appendChild(taskDescription);

        topContentDiv.appendChild(checkbox);
        topContentDiv.appendChild(contentDiv);
        topContentDiv.appendChild(editIcon);
        topContentDiv.appendChild(deleteIcon);
        topContentDiv.appendChild(showMoreDiv);

        liElement.appendChild(topContentDiv);

        const moreInfoDiv = document.createElement('div');
        moreInfoDiv.id = 'moreInfo_' + id_tarefa;
        moreInfoDiv.className = 'moreInfoDiv';
        moreInfoDiv.style.display = 'none';

        const creationDateP = document.createElement('p');
        creationDateP.textContent = 'Creation Date: ' + formattedDate;
        const priorityP = document.createElement('p');
        priorityP.textContent = 'Priority: ' + priorityMap[prioridade];
        const difficultyP = document.createElement('p');
        difficultyP.textContent = 'Difficulty: ' + difficultyMap[dificuldade];

        moreInfoDiv.appendChild(creationDateP);
        moreInfoDiv.appendChild(priorityP);
        moreInfoDiv.appendChild(difficultyP);

        liElement.appendChild(moreInfoDiv);

        const difficultyDiv = document.createElement('div');
        difficultyDiv.className = 'difficulty ' + difficultyMap[dificuldade];

        liElement.appendChild(difficultyDiv);

        taskList.appendChild(liElement);
    });

    // Verifica se a lista de tarefas está vazia e exibe uma mensagem apropriada
    if (tasks.length === 0) {
        taskList.innerHTML = "<p>Task list is empty</p>";
    }

    if(completedBtn.classList.contains('button-active')){
        filterTasks('completed', tasks);
    } else if(incompletedBtn.classList.contains('button-active')){
        filterTasks('incompleted', tasks);
    } else {
        filterTasks('', tasks);
    }
}

function handleTasksVisibility(element) {
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
    filterTasks,
    fetchUserData
}
