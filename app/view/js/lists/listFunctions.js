import { fetchLists } from "./listFilter.js";
import notificate from "../notification.js";

async function deleteList(event){
    const listID = parseInt(event.target.parentNode.parentNode.id);

    try {
        const response = await fetch(`ListaController.php?action=delete&id=${listID}`, {
            method: "DELETE",
        });

        if (!response.ok) {
            throw new Error('The request to the server has failed');
        }

        const responseData =  await response.json();
        console.log(responseData);

        // Atualiza a tela com as listas atuais
        fetchLists();

    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

export {
    deleteList
}
