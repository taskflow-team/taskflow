import { groupModal } from "./groupsModal.js";
import { deleteGroup } from "./groupsFunctions.js";
import notificate from "../notification.js";

const groupsHolder = document.querySelector('.groups-holder');

async function fetchGroups(){
    try {
        const response = await fetch('GrupoController.php?action=list');
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            throw new Error(responseData.error);
        }

        updateGroups(responseData.data);

    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

fetchGroups();

function updateGroups(groups) {
    groupsHolder.innerHTML = '';

    groups.forEach((group) => {
        const {
            idtb_grupo,
            codigo_convite,
            nome,
            id_usuario,
            id_grupo,
            administrador,
            pontos
        } = group;

        const groupElement = document.createElement('p');
        groupElement.textContent = `Invitation Code: ${codigo_convite}, Name: ${nome}, Admin: ${administrador}, Points: ${pontos}`;

        const renameBtn = document.createElement('button')
        renameBtn.id = 'renameBtn';
        renameBtn.innerText = 'Rename';
        renameBtn.addEventListener('click', () => groupModal(id_usuario, 'rename', group));

        const deleteBtn = document.createElement('button');
        deleteBtn.id = 'deleteBtn';
        deleteBtn.dataset.id = id_grupo;
        deleteBtn.innerText = 'Delete';
        deleteBtn.addEventListener('click', deleteGroup);

        groupElement.appendChild(renameBtn);
        groupElement.appendChild(deleteBtn);

        groupsHolder.appendChild(groupElement);
    });
}

export {
    fetchGroups,
}