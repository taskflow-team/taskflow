import { createList } from './listFunctions.js';

function listModal(){
    const modalBg = document.createElement('div');
    modalBg.className = 'modal-bg';

    const modal = document.createElement('div');
    modal.className = 'modal create-list-modal';
    modal.style.display = 'flex';

    const input = document.createElement('input');
    input.className = 'dark-input';
    input.id = 'list-name-input';
    input.setAttribute('type', 'text');
    input.setAttribute('placeholder', 'Enter the list name here');

    const createBtn = document.createElement('button');
    createBtn.className = 'btn btn success';
    createBtn.innerText = 'Create';
    createBtn.addEventListener('click', createList);

    const closeIcon = document.createElement('i');
    closeIcon.className = 'fa-solid fa-x';
    closeIcon.addEventListener('click', closeModal);

    modal.appendChild(input);
    modal.appendChild(createBtn);
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
    }, 500);
}


export {
    listModal,
    closeModal
}
