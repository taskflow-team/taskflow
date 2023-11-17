// Esse arquivo armazena as funções que podem
// ser desempenhadas dentro de uma tarefa

import notificate from '../notification.js';
import { fetchTaskList, fetchUserData } from './tasksFilter.js';

const taskForm = document.querySelector('#frmTarefa');
const createBtn = document.querySelector('#submitTaskButton');

if(IS_ADMIN == 1)
{
    async function createTask(event) {
        event.preventDefault();

        const userID = parseInt(document.querySelector('#idUsuario').value);

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
                    userID: userID,
                    groupID: GROUP_ID,
                    listID: LIST_ID 
                })
            };

            const response = await fetch(BASE_URL + '/controller/TarefaController.php?action=save&listId=' + LIST_ID, reqConfigs);
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

    createBtn.addEventListener('click', createTask);
}

async function completeTask(event) {
    const checkbox = event.target;
    const taskId = checkbox.parentElement.parentElement.id;

    const userID = parseInt(document.querySelector('#idUsuario').value);

    const taskCompleted = checkbox.checked;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                taskId: taskId,
                userID: userID,
                groupID: GROUP_ID,
                taskCompleted: taskCompleted
            })
        };

        const response = await fetch(BASE_URL + '/controller/TarefaController.php?action=completeTask&listId=' + LIST_ID, reqConfigs);
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
        fetchUserData();
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
    const userId = parseInt(document.querySelector('#idUsuario').value);

    const checkbox = document.querySelector(`#checkbox-task[data-id="${taskId}"]`);
    const taskCompleted = checkbox.checked;

    try {
        const response = await fetch(BASE_URL + '/controller/TarefaController.php?action=delete&listId=' + LIST_ID + "&taskId=" + taskId + "&userId=" + userId + "&taskCompleted=" + taskCompleted, {
            method: "DELETE",
        });

        if (!response.ok) {
            notificate('error', 'Error', 'The request to the server has failed');
        } else {
            // Realiza um fetch da lista de tarefas atualizada após excluir a tarefa
            fetchTaskList();
            fetchUserData();
        }
    } catch (error) {
        notificate('error', 'Error', error);
    }
}

export {
    completeTask,
    deleteTask
}
