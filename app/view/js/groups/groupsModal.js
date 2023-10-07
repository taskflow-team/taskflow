import fetchUserData from "../user/fetchUserData.js";
import { createGroup, joinGroup, renameGroup } from './groupsFunctions.js';
import notificate from "../notification.js";

function groupModal(userID, type, group){
    const modalBg = document.createElement('div');
    modalBg.className = 'modal-bg';

    const modal = document.createElement('div');
    modal.className = 'modal create-group-modal';
    modal.style.display = 'flex';

    const input = document.createElement('input');
    input.className = 'dark-input';
    input.id = 'group-name-input';
    input.setAttribute('type', 'text');
    input.setAttribute('placeholder', 'Enter the group name here');

    const submitBtn = document.createElement('button');
    submitBtn.className = 'btn btn success';
    submitBtn.innerText = type == 'create' ? 'Create' : 'Rename';

    if(type == 'join')
    {
        input.className = 'dark-input';
        input.id = 'group-code-input';
        input.setAttribute('type', 'text');
        input.setAttribute('placeholder', 'Enter the group code here');

        submitBtn.innerText = "Join"

        submitBtn.addEventListener('click', () => joinGroup(userID));
    }
    else if(type == 'create'){
        input.className = 'dark-input';
        input.id = 'group-name-input';
        input.setAttribute('type', 'text');
        input.setAttribute('placeholder', 'Enter the group name here');

        submitBtn.innerText = "Create"

        submitBtn.addEventListener('click', () => createGroup(userID));
    } else if(type == 'rename') {
        input.value = group.nome;

        submitBtn.innerText = "Rename"

        submitBtn.addEventListener('click', () => renameGroup(group.id_grupo));
    }

    const closeIcon = document.createElement('i');
    closeIcon.className = 'fa-solid fa-x';
    closeIcon.addEventListener('click', closeModal);

    modal.appendChild(input);
    modal.appendChild(submitBtn);
    modal.appendChild(closeIcon);

    document.body.appendChild(modalBg);
    document.body.appendChild(modal);
}

function closeModal(){
    let modalBg = document.querySelector('.modal-bg');
    let modal = document.querySelector('.modal');

    modal.className = 'modal create-list-modal close-animation';

    setTimeout(() => {
        modalBg.remove();
        modal.remove();
    }, 1000);
}

const user = await fetchUserData();
const createGroupBtn = document.querySelector('#btn-create-group');
createGroupBtn.addEventListener('click', () => groupModal( user.id, 'create'));

const joinGroupBtn = document.querySelector('#btn-join-group');
joinGroupBtn.addEventListener('click', () => groupModal( user.id, 'join'));

export {
    groupModal,
    closeModal,
}
