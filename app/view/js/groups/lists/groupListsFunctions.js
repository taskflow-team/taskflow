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

export {
    createListGroup
}