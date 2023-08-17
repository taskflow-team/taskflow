// Fetch lists and filter them
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
    console.log(lists);

    pseudoBody.innerHTML = '';
    pseudoBody.innerText = '';

    const createListDiv = document.createElement('div');
    createListDiv.className = 'list-card create-list';

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


        leftInfo.appendChild(barsIcon);
        infoDiv.appendChild(leftInfo);
        infoDiv.appendChild(rigthInfo);

        listCard.appendChild(cardTitle);
        listCard.appendChild(infoDiv);

        pseudoBody.appendChild(listCard);
    });
}

export {
    fetchLists,
}
