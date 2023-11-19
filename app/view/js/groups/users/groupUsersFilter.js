import notificate from "../../notification.js";

const usersHolder = document.querySelector('.usernames-holder');

async function fetchGroupUsers(groupId){
    try {
        const response = await fetch(BASE_URL + `/controller/GrupoController.php?action=listGroupUsers&groupId=${groupId}`);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            throw new Error(responseData.error);
        }

        updateUsersSidebar(responseData.data);

    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function fetchUserData() {
    const reqConfigs = {
        method: "GET",
        headers: {
            'Content-type': 'application/json'
        },
    };

    try {
        const response = await fetch(BASE_URL + '/controller/UsuarioController.php?action=getUserData', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        return responseData.user;

    } catch (error) {
        notificate('error', 'Error', error);
    }
}

const userId = await fetchUserData();

function updateUsersSidebar(users) {
    usersHolder.innerHTML = '';

    users.forEach((user) => {
        const userElement = document.createElement('div');
        userElement.className = 'user-element';
        userElement.innerText = user.login; 
        usersHolder.appendChild(userElement);

        if(IS_ADMIN == 1 && user.id_usuario != userId.id)
        {
            const turnAdmin = document.createElement('button');
            turnAdmin.className = 'user-buttons';
            turnAdmin.innerText = user.administrador == 1 ? 'Remove Admin' : 'Make Admin';
            turnAdmin.dataset.id = user.id_usuario;
            usersHolder.appendChild(turnAdmin);

            const banUser = document.createElement('button');
            banUser.className = 'user-buttons';
            banUser.innerText = 'Ban';
            banUser.dataset.id = user.id_usuario;
            usersHolder.appendChild(banUser);

            turnAdmin.addEventListener('click', () => toggleUserAdminStatus(user.id_usuario, user.administrador, turnAdmin));
            banUser.addEventListener('click', () => banUserFromGroup(user.id_usuario));
        }
    });
}

async function toggleUserAdminStatus(userId, isAdmin, button) {
    try {
        const action = isAdmin == 1 ? 'removeAdmin' : 'turnToAdmin';
        const response = await fetch(BASE_URL + `/controller/GrupoController.php?action=${action}&userId=${userId}`, {
            method: 'POST'
        });
        const responseData = await response.json();

        if (!response.ok) {
            throw new Error(responseData.message);
        }

        // Update button text based on new admin status
        button.innerText = isAdmin == 1 ? 'Make Admin' : 'Remove Admin';

        notificate('success', 'Success', isAdmin == 1 ? 'Admin rights removed' : 'User turned to admin successfully');
    } catch (error) {
        notificate('error', 'Error', error.message);
    }

    fetchGroupUsers(GROUP_ID);
}

async function banUserFromGroup(userId) {
    try {
        const response = await fetch(BASE_URL + `/controller/GrupoController.php?action=banUser&userId=${userId}`, {
            method: 'POST'
        });
        const responseData = await response.json();

        if (!response.ok) {
            throw new Error(responseData.message);
        }

        notificate('success', 'Success', 'User banned successfully');
    } catch (error) {
        notificate('error', 'Error', error.message);
    }

    fetchGroupUsers(GROUP_ID);
}

fetchGroupUsers(GROUP_ID); 

export {
    fetchGroupUsers,
}
