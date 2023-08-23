// Fetch lists and filter them
import { deleteList } from "./listFunctions.js";
import { listModal } from "./listModal.js";
import notificate from "../notification.js";

const pseudoBody = document.querySelector('.pseudo-body');

async function fetchLists(){
    try {
        const response = await fetch('ListaController.php?action=list');
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
    pseudoBody.innerHTML = '';
    pseudoBody.innerText = '';

    const createListDiv = document.createElement('div');
    createListDiv.className = 'list-card create-list';
    createListDiv.addEventListener('click', () => listModal('create'));

    const addIcon = document.createElement('i');
    addIcon.className = 'fa-regular fa-plus';

    createListDiv.appendChild(addIcon);
    pseudoBody.appendChild(createListDiv);

    lists.forEach((list) => {
        const listCard = document.createElement('div');
        listCard.className = 'list-card';
        listCard.id = list.id_lista;

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

        leftInfo.appendChild(barsIcon);
        infoDiv.appendChild(leftInfo);
        infoDiv.appendChild(rigthInfo);

        const listCardBody = document.createElement('div');
        listCardBody.className = 'list-card-body';
        listCardBody.addEventListener('click', () => {
            window.location.href = `tasksIndex.php?listId=${list.id_lista}`;
        });

        listCard.appendChild(cardTitle);
        listCard.appendChild(infoDiv);
        listCard.appendChild(actionsDiv);
        listCard.appendChild(listCardBody); 
        listCard.appendChild(dotsIcon);

        pseudoBody.appendChild(listCard);
    });
}

function createActionsDiv(list, actionsDiv) {
    const renameBtn = document.createElement('button');
    renameBtn.innerText = 'Rename';
    renameBtn.className = 'rename-btn';
    renameBtn.addEventListener('click', () => listModal('edit', list.id_lista, list.nome_lista));

    const deleteBtn = document.createElement('button');
    deleteBtn.innerText = 'Delete';
    deleteBtn.className = 'delete-btn';
    deleteBtn.addEventListener('click', deleteList);
    deleteBtn.style.padding = '0 25px 10px 25px';

    actionsDiv.appendChild(renameBtn);
    actionsDiv.appendChild(deleteBtn);
}

function showActions(actionsDiv) {
    actionsDiv.style.visibility = actionsDiv.style.visibility == 'visible' ? 'hidden' : 'visible';
}

export {
    fetchLists,
}
