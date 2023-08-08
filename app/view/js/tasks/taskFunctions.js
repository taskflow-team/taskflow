import notificate from '../notification.js';
import { fetchTaskList, filterTasks } from './tasksFilter.js';

const taskForm = document.querySelector('#frmTarefa');

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

export {
    completeTask,
    deleteTask
}
