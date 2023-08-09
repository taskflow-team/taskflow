// Esse arquivo armazena todo o código que diz respeito a
// Editar tarefas no banco de dados

import notificate from "../notification.js";
import formatDate from '../formatDate.js';
import { fetchTaskList } from './tasksFilter.js';

const taskEditForm = document.querySelector('#frmEditTarefa');
const taskEditModal = document.querySelector('#taskModal');
const closeEditBtn = document.querySelector('.close');

function openEditModal(taskId, taskName, taskDescription, taskDifficulty, taskPriority, taskPoints) {
    taskEditModal.style.display = "block";

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
}

function closeEditModal() {
    taskEditModal.style.display = "none";
    fetchTaskList();
}

closeEditBtn.addEventListener('click', closeEditModal);

$(document).on("click", ".editBtn", function () {
    const taskId = $(this).data("task-id");
    const taskName = $(this).data("task-name");
    const taskDescription = $(this).data("task-description");
    const taskDifficulty = $(this).data("task-difficulty");
    const taskPriority = $(this).data("task-priority");
    const taskPoints = $(this).data("task-points");

    openEditModal(taskId, taskName, taskDescription, taskDifficulty, taskPriority, taskPoints);
});

window.onclick = function(event) {
  if (event.target == taskEditModal) {
    taskEditModal.style.display = "none";
  }
}