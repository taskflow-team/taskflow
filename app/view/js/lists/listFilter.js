// Fetch lists and filter them
import { deleteList } from "./listFunctions.js";
import { listModal } from "./listModal.js";
import notificate from "../notification.js";

const pseudoBody = document.querySelector('.pseudo-body');
const listsHolder = document.querySelector('.lists-holder');

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
            window.location.href = `../view/pages/tasks/index.php?listId=${list.id_lista}&listName=${list.nome_lista}`;
        });

        listCard.appendChild(cardTitle);
        listCard.appendChild(infoDiv);
        listCard.appendChild(actionsDiv);
        listCard.appendChild(listCardBody);
        listCard.appendChild(dotsIcon);

        listsHolder.appendChild(listCard);
    });
}

// Função para obter os dados do usuário do servidor
async function fetchUserData() {
    const reqConfigs = {
        method: "GET",
        headers: {
            'Content-type': 'application/json'
        },
    };

    try {
        const response = await fetch('UsuarioController.php?action=getUserData', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        updateUserData(responseData.user);

    } catch (error) {
        notificate('error', 'Error', error);
    }
}

// Chama a função fetchUserData para carregar os dados do usuário na carga da página
fetchUserData();

function updateUserData(user) {
    const {
        id,
        nome,
        email,
        pontos,
        nivel,
        tarefas_concluidas
    } = user;

    const emeraldsHolder = document.querySelector('#emeralds-holder');
    emeraldsHolder.innerText = pontos;
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
