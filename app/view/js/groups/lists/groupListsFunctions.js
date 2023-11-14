import { fetchLists } from "./groupListsFilter.js";
import { closeModal } from "./groupListsModal.js";
import notificate from "../../notification.js";

async function createListGroup(){
    const listName = document.querySelector('#list-name-input').value;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                listName: listName,
                userID: null,
                groupID: GROUP_ID
            })
        };

        const response = await fetch(BASE_URL + '/controller/ListaController.php?action=save', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to create List');
        }

        closeModal();
        fetchLists();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function renameListGroup(listId){
    let listName = document.querySelector('#list-name-input').value;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                listId: listId,
                listName: listName
            })
        };

        const response = await fetch(BASE_URL + '/controller/ListaController.php?action=rename', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to create List');
        }

        closeModal();
        fetchLists();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function deleteListGroup(event){
    const deleteBtn = event.target;
    const listID = deleteBtn.id;

    deleteBtn.style.padding = '0px';

    deleteBtn.innerText = '';

    deleteBtn.classList.remove('hover-enabled');

    const confirmBtn = document.createElement('span');
    confirmBtn.innerText = 'Confirm';
    confirmBtn.className = 'confirm-btn';
    confirmBtn.addEventListener('click', async() => {
        event.stopPropagation();
        deleteBtn.innerHtml = '';
        deleteBtn.innerText = '';

        try {
            const response = await fetch(BASE_URL + `/controller/ListaController.php?action=delete&id=${listID}`, {
                method: "DELETE",
            });

            if (!response.ok) {
                throw new Error('The request to the server has failed');
            }

            const responseData =  await response.json();
            notificate('success', 'Success', responseData.message)

            // Atualiza a tela com as listas atuais
            fetchLists();

        } catch (error) {
            notificate('error', 'Error', error.message);
        }
    })

    const cancelBtn = document.createElement('span');
    cancelBtn.innerText = 'Cancel';
    cancelBtn.className = 'cancel-btn';

    deleteBtn.appendChild(confirmBtn);
    deleteBtn.appendChild(cancelBtn);

    cancelBtn.addEventListener('click', () => {
        event.stopPropagation();
        deleteBtn.innerHtml = '';
        deleteBtn.innerText = 'Delete';
        deleteBtn.style.padding = '10px 25px';
        deleteBtn.classList.add('hover-enabled');
    });
}

export {
    createListGroup,
    renameListGroup,
    deleteListGroup
}