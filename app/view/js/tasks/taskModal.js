// Esse arquivo armazena todo o código que diz respeito a
// Editar tarefas no banco de dados

import notificate from "../notification.js";
import { fetchTaskList } from './tasksFilter.js';

const taskEditModal = document.querySelector('#taskModal');
const closeEditBtn = document.querySelector('.close');
const taskModalEditBtn = document.querySelector('.editTaskBtn');

function openEditModal(taskId, taskName, taskDescription, taskDifficulty, taskPriority, taskPoints, idtb_listas) {
    taskEditModal.classList.add('show');

    taskModalEditBtn.dataset.id = taskId;

    const taskEditName = document.querySelector('.nameEditTask');
    const taskEditDescription = document.querySelector('#taskDescription');

    const taskEditDifficulty1 = document.querySelector('#difficulty1');
    const taskEditDifficulty2 = document.querySelector('#difficulty2');
    const taskEditDifficulty3 = document.querySelector('#difficulty3');

    const taskEditPriority1 = document.querySelector('#priority1');
    const taskEditPriority2 = document.querySelector('#priority2');
    const taskEditPriority3 = document.querySelector('#priority3');

    const taskEditPoints = document.querySelector('#taskPoints');

    taskEditName.value = taskName;
    taskEditDescription.innerHTML = taskDescription;

    switch(taskDifficulty) {
        case 1:
            taskEditDifficulty1.checked = true;
            break;
        case 2:
            taskEditDifficulty2.checked = true;
            break;
        case 3:
            taskEditDifficulty3.checked = true;
            break;
    }

    switch(taskPriority) {
        case 1:
            taskEditPriority1.checked = true;
            break;
        case 2:
            taskEditPriority2.checked = true;
            break;
        case 3:
            taskEditPriority3.checked = true;
            break;
    }

    taskEditPoints.value = taskPoints;
    populateTaskLists(idtb_listas);
}

function closeEditModal(element) {
    if (element.target == closeEditBtn) {
        taskEditModal.classList.remove('show');
    } else if (element.target == taskModalEditBtn) {
        const taskForm = document.querySelector('#frmEditTarefa');

        // Checa se todos os campos do formulário marcados como required estão preenchidos
        if (!taskForm.checkValidity()) {
            taskForm.reportValidity();
            return;
        }

        taskEditModal.classList.remove('show');
        editTaskModal();
    }
}

async function populateTaskLists(currentListId) {
    const taskListSelect = document.querySelector('#taskListSelection');
    let grupo = GROUP_ID == null ? `list` : `listGroup&groupId=${GROUP_ID}`;

    try {
        const response = await fetch(BASE_URL + '/controller/ListaController.php?action=' + grupo);
        const data = await response.json();

        if (response.ok) {
            taskListSelect.innerHTML = '<option value="0">Select a list</option>';
            data.data.forEach(list => {
                const option = document.createElement('option');
                option.value = list.id_lista;
                option.textContent = list.nome_lista;

                if (list.id_lista === currentListId) {
                    option.selected = true;
                }

                taskListSelect.appendChild(option);
            });
        } else {
            notificate('error', 'Error', 'Failed to fetch task lists');
        }
    } catch (error) {
        notificate('error', 'Error', error);
    }
}


async function editTaskModal() {
    const taskForm = document.querySelector('#frmEditTarefa');
    const taskId = Number(taskModalEditBtn.dataset.id);

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
                taskId: taskId,
                formData: formData
            })
        };

        const response = await fetch(BASE_URL + '/controller/TarefaController.php?action=edit', reqConfigs);
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

if(IS_ADMIN == 1)
{
    closeEditBtn.addEventListener('click', closeEditModal);
    taskModalEditBtn.addEventListener('click', closeEditModal);
}

export {
    openEditModal
}
