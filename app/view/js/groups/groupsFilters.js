import { groupModal } from "./groupsModal.js";
import { deleteGroup, leaveGroup } from "./groupsFunctions.js";
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
        groupElement.textContent = `Group Name: ${nome}, Points: ${pontos}`;

        if(administrador)
        {
            const groupElement = document.createElement('p');
            groupElement.textContent = `Name: ${nome}, Points: ${pontos}`;

            const invitationCode = document.createElement('p');
            invitationCode.textContent = `Invitation Code: ${codigo_convite}`;

            const adminElement = document.createElement('p');
            groupElement.textContent = `ADMIN`;

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

            groupsHolder.appendChild(invitationCode);
            groupsHolder.appendChild(adminElement);

            groupsHolder.appendChild(groupElement);
        }
        const leaveBtn = document.createElement('button')
        leaveBtn.id = 'leaveBtn';
        leaveBtn.dataset.id = id_grupo;
        leaveBtn.innerText = 'Leave';
        leaveBtn.addEventListener('click', leaveGroup);

        groupElement.appendChild(leaveBtn);

        groupsHolder.appendChild(groupElement);
    });
}

export {
    fetchGroups,
}