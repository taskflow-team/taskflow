import notificate from "../../notification.js";
import { listModal, closeModal } from "./groupListsModal.js";

const listsBtn = document.querySelector('#btn-lists');
const rewardsBtn = document.querySelector('#btn-rewards');
const listsHolder = document.querySelector('#lists-holder');

async function fetchLists(){
    const reqConfigs = {
        method: "GET",
        headers: {
            'Content-type': 'application/json'
        },
    };
    
    try {
        const response = await fetch(BASE_URL + '/controller/ListaController.php?action=listGroup&groupId=' + GROUP_ID, reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            throw new Error(responseData.error);
        }

        updateLists(responseData.data);

    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

fetchLists();

function updateLists(lists){
    listsHolder.innerHTML = '';
    listsHolder.innerText = '';

    const createListDiv = document.createElement('div');
    createListDiv.className = 'list-card create-list';
    createListDiv.addEventListener('click', () => listModal('create'));

    const addIcon = document.createElement('i');
    addIcon.className = 'fa-regular fa-plus';

    createListDiv.appendChild(addIcon);
    listsHolder.appendChild(createListDiv);

    lists.forEach((list) => {
        const listCard = document.createElement('div');
        listCard.className = 'list-card';
        listCard.id = list.id_lista;
        listCard.addEventListener('click', () => {
            window.location.href = `../view/pages/tasks/index.php?listId=${list.id_lista}&listName=${list.nome_lista}`;
        });

        const cardTitle = document.createElement('h3');
        cardTitle.innerText = list.nome_lista;

        const infoDiv = document.createElement('div');
        infoDiv.className = 'info-div';

        const leftInfo = document.createElement('div');
        leftInfo.className = 'left-div';

        const rigthInfo = document.createElement('div');
        rigthInfo.className = 'right-div';
        rigthInfo.innerHTML = '<span>List</span>';

        const barsIcon = document.createElement('i');
        barsIcon.className = 'fa-solid fa-bars';

        const dotsIcon = document.createElement('i');
        dotsIcon.className = 'fa-solid fa-ellipsis-vertical dots';
        dotsIcon.addEventListener('click', (event) => {
            event.stopPropagation();
            showActions(actionsDiv);
        });

        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'actions-div';
        actionsDiv.style.visibility = 'hidden';
        createActionsDiv(list, actionsDiv);

        const buttonsDiv = document.createElement('div');
        buttonsDiv.className = 'buttons-div';

        leftInfo.appendChild(barsIcon);
        infoDiv.appendChild(leftInfo);
        infoDiv.appendChild(rigthInfo);

        buttonsDiv.appendChild(dotsIcon);
        buttonsDiv.appendChild(actionsDiv);

        listCard.appendChild(cardTitle);
        listCard.appendChild(infoDiv);
        listCard.appendChild(buttonsDiv);
    
        listsHolder.appendChild(listCard);
    });
}

function createActionsDiv(list, actionsDiv) {
    const renameBtn = document.createElement('button');
    renameBtn.innerText = 'Rename';
    renameBtn.className = 'rename-btn';
    renameBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        listModal('edit', list.id_lista, list.nome_lista)
    });

    const deleteBtn = document.createElement('button');
    deleteBtn.id = list.id_lista;
    deleteBtn.innerText = 'Delete';
    deleteBtn.className = 'delete-btn hover-enabled';
    deleteBtn.style.padding = '10px 25px';
    deleteBtn.addEventListener('click', (event) => { 
        event.stopPropagation();
        deleteList(event);
    });

    actionsDiv.appendChild(renameBtn);
    actionsDiv.appendChild(deleteBtn);
}


function showActions(actionsDiv) {
    actionsDiv.style.visibility = actionsDiv.style.visibility == 'visible' ? 'hidden' : 'visible';
}

listsBtn.addEventListener("click", fetchLists);

export {
    fetchLists,
}
