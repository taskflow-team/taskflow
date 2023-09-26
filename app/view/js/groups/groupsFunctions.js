import { closeModal } from "./groupsModal.js";
import notificate from "../notification.js";

async function createGroup(userID){
    let groupName = document.querySelector('#group-name-input').value;
    let groupCode = randomString();

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                groupName: groupName,
                groupCode: groupCode,
                userID: userID
            })
        };

        const response = await fetch('GrupoController.php?action=save', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            notificate(
                'error',
                'Erro',
                responseData.message
            );
        }
        else {
            notificate(
                'success',
                'Success',
                responseData.message
            );
        }

        closeModal();
        // fetchRewards();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function joinGroup(userID){
    let groupCode = document.querySelector('#group-code-input').value;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                groupCode: groupCode,
                userID: userID
            })
        };

        const response = await fetch('GrupoController.php?action=join', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            notificate(
                'error',
                'Erro',
                responseData.message
            );
        }
        else {
            notificate(
                'success',
                'Success',
                responseData.message
            );
        }

        closeModal();
        // fetchRewards();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

function randomString() {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    var string_length = 8;
    var randomstring = '';
    for (var i = 0; i < string_length; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars[rnum];
    }

    return randomstring;
}

export {
    createGroup,
    joinGroup
}