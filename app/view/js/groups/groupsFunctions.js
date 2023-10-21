import { fetchGroups } from "./groupsFilters.js";
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

        joinGroup(userID, groupCode, 1)
        closeModal();
        fetchGroups();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function joinGroup(userID, groupCode, isAdministrator){
    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                groupCode: groupCode,
                userID: userID,
                isAdministrator: isAdministrator
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
        fetchGroups();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function renameGroup(groupId){
    let groupName = document.querySelector('#group-name-input').value;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                groupId: groupId,
                groupName: groupName
            })
        };

        const response = await fetch('GrupoController.php?action=rename', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to create List');
        }

        closeModal();
        fetchGroups();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function deleteGroup(event){
    const deleteBtn = event.target;
    const groupId = deleteBtn.dataset.id;

    try {
        const response = await fetch(`GrupoController.php?action=delete&id=${groupId}`, {
            method: "DELETE",
        });

        if (!response.ok) {
            throw new Error('The request to the server has failed');
        }

        const responseData =  await response.json();
        notificate('success', 'Success', responseData.message)

        fetchGroups();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function leaveGroup(event){
    const leaveBtn = event.target;
    const groupId = leaveBtn.dataset.id;

    try {
        const response = await fetch(`GrupoController.php?action=leaveGroup&id=${groupId}`, {
            method: "DELETE",
        });

        if (!response.ok) {
            throw new Error('The request to the server has failed');
        }

        const responseData =  await response.json();
        notificate('success', 'Success', responseData.message)

        fetchGroups();
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
    joinGroup,
    renameGroup,
    deleteGroup,
    leaveGroup
}