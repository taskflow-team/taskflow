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

        const groupCard = document.createElement('div');
        groupCard.className = 'group-card';
        groupCard.addEventListener('click', () => {
            window.location.href = `../view/pages/groupHome/groupHome.php?groupId=${id_grupo}&groupName=${nome}&isAdmin=${administrador}`;
        });

        const groupName = document.createElement('h3');
        groupName.innerText = `${nome}`;

        groupCard.appendChild(groupName);

        const adminElement = document.createElement('p');
        adminElement.textContent = `Functions:`;

        const functions = document.createElement('div');
        functions.className = 'group-functions';

        if(administrador){
            const invitationCode = document.createElement('p');
            invitationCode.textContent = `Invitation Code: ${codigo_convite}`;

            const renameBtn = document.createElement('button')
            renameBtn.id = 'renameBtn';
            renameBtn.className = 'btn btn-success';
            renameBtn.addEventListener('click', () => groupModal(id_usuario, 'rename', group));

            const editIcon = document.createElement('i');
            editIcon.className = 'fa fa-pencil-square';

            renameBtn.appendChild(editIcon);

            const deleteBtn = document.createElement('button');
            deleteBtn.id = 'deleteBtn';
            deleteBtn.className = 'btn btn-danger';
            deleteBtn.dataset.id = id_grupo;
            deleteBtn.addEventListener('click', deleteGroup);

            const deleteIcon = document.createElement('i');
            deleteIcon.className = 'fa fa-trash';

            deleteBtn.appendChild(deleteIcon);

            functions.appendChild(renameBtn);
            functions.appendChild(deleteBtn);

            groupCard.appendChild(invitationCode);
            groupCard.appendChild(adminElement);
        }

        const leaveBtn = document.createElement('button');
        leaveBtn.id = 'leaveBtn';
        leaveBtn.className = 'btn btn-warning';
        leaveBtn.dataset.id = id_grupo;
        leaveBtn.addEventListener('click', leaveGroup);

        const leaveIcon = document.createElement('i');
        leaveIcon.className = 'fa fa-sign-out';

        leaveBtn.appendChild(leaveIcon);

        functions.appendChild(leaveBtn);

        groupCard.appendChild(functions);

        groupsHolder.appendChild(groupCard);
    });
}

export {
    fetchGroups,
}
