import { fetchLists } from "./listFilter.js";
import { closeModal } from "./listModal.js";
import notificate from "../notification.js";

async function createList(){
    const listName = document.querySelector('#list-name-input').value;
    const userID = document.querySelector('#idUsuario').value;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                listName: listName,
                userID: userID
            })
        };

        const response = await fetch('ListaController.php?action=save', reqConfigs);
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

async function deleteList(event){
    const deleteBtn = event.target;
    const listID = deleteBtn.parentNode.parentNode.id;

    deleteBtn.style.padding = '0';

    deleteBtn.innerText = '';

    const confirmBtn = document.createElement('span');
    confirmBtn.innerText = 'Confirm';
    confirmBtn.className = 'confirm-btn';
    confirmBtn.addEventListener('click', async() => {
        deleteBtn.innerHtml = '';
        deleteBtn.innerText = '';

        try {
            const response = await fetch(`ListaController.php?action=delete&id=${listID}`, {
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
        deleteBtn.innerHtml = '';
        deleteBtn.innerText = 'Delete';
        deleteBtn.style.padding = '0 25px 10px 25px';
    });
}

export {
    deleteList,
    createList
}
