import { createList } from './listFunctions.js';

function createListModal(){
    const modalBg = document.createElement('div');
    modalBg.className = 'modal-bg';

    const modal = document.createElement('div');
    modal.className = 'modal create-list-modal';
    modal.style.display = 'flex';

    const input = document.createElement('input');
    input.className = 'dark-input';
    input.setAttribute('type', 'text');
    input.setAttribute('placeholder', 'Enter the list name here');

    const createBtn = document.createElement('button');
    createBtn.innerText = 'Create';
    createBtn.addEventListener('click', createList);

    modal.appendChild(input);
    modal.appendChild(createBtn);

    document.body.appendChild(modalBg);
    document.body.appendChild(modal);
}


export default createListModal;
