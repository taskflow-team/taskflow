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

function updateUsersSidebar(users) {
    usersHolder.innerHTML = '';

    users.forEach((user) => {
        const userElement = document.createElement('div');
        userElement.className = 'user-element';
        userElement.innerText = user.login; 
        usersHolder.appendChild(userElement);
    });
}

fetchGroupUsers(GROUP_ID); 

export {
    fetchGroupUsers,
}
