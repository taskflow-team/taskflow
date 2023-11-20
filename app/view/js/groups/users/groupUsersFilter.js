import notificate from "../../notification.js";

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
    const membersTable = document.querySelector('.members-table');
    membersTable.innerHTML = '';

    const tableHeader = document.createElement('tr');

    const userNameCell = document.createElement('th');
    userNameCell.innerText = 'Name';

    const userRoleCell = document.createElement('th');
    userRoleCell.innerText = 'Role';

    const levelCell = document.createElement('th');
    levelCell.innerText = 'Level';

    const completedTasksCell = document.createElement('th');
    completedTasksCell.innerText = 'Completed Tasks';

    const userEmeraldsCell = document.createElement('th');
    userEmeraldsCell.innerText = 'Emeralds';

    const adminFunctions = document.createElement('th');
    adminFunctions.innerText = IS_ADMIN == 1 ? 'Admin Functions' : '';

    tableHeader.appendChild(userNameCell);
    tableHeader.appendChild(userRoleCell);
    tableHeader.appendChild(levelCell);
    tableHeader.appendChild(completedTasksCell);
    tableHeader.appendChild(userEmeraldsCell);
    tableHeader.appendChild(adminFunctions);

    membersTable.appendChild(tableHeader);

    users.forEach((user) => {
        console.log(user);

        const userRow = document.createElement('tr');
        userRow.className = 'user-row';

        const userNameCol = document.createElement('td');
        userNameCol.innerText = user.nome_usuario;
        userNameCol.className = 'user-name-col';

        const roleCol = document.createElement('td');
        roleCol.innerText = user.administrador == 1 ? 'admin' : 'member';
        roleCol.className = 'user-role-col';

        const levelCol = document.createElement('td');
        // levelCol.innerText = user.nivel;
        levelCol.className = 'user-level-col';

        const completedTasksCol = document.createElement('td');
        // completedTasksCol.innerText = user.pontos;
        completedTasksCol.className = 'completed-tasks-col';

        const emeraldsCol = document.createElement('td');
        emeraldsCol.innerText = user.pontos;
        emeraldsCol.className = 'user-emeralds-col';

        userRow.appendChild(userNameCol);
        userRow.appendChild(roleCol);
        userRow.appendChild(levelCol);
        userRow.appendChild(completedTasksCol);
        userRow.appendChild(emeraldsCol);

        const adminFunctionsCell = document.createElement('td');
        adminFunctionsCell.className = 'admin-functions-col'

        if(IS_ADMIN == 1 && user.id_usuario != userId.id){
            const turnAdmin = document.createElement('button');
            turnAdmin.className = 'user-buttons btn btn-warning';
            turnAdmin.innerText = user.administrador == 1 ? 'Remove Admin' : 'Make Admin';
            turnAdmin.dataset.id = user.id_usuario;

            const banUser = document.createElement('button');
            banUser.className = 'user-buttons btn btn-danger';
            banUser.innerText = 'Ban';
            banUser.dataset.id = user.id_usuario;

            turnAdmin.addEventListener('click', () => toggleUserAdminStatus(user.id_usuario, user.administrador, turnAdmin));
            banUser.addEventListener('click', () => banUserFromGroup(user.id_usuario));

            adminFunctionsCell.appendChild(turnAdmin);
            adminFunctionsCell.appendChild(banUser);
        }

        userRow.appendChild(adminFunctionsCell);

        membersTable.appendChild(userRow);
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
