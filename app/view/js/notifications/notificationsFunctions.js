import notificate from "../notification.js";

const notificationsHolder = document.querySelector('.notifications-holder');

async function fetchNotifications() {
    try {
        const response = await fetch(`NotificacaoController.php?action=list&userId=${USER}`);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            throw new Error(responseData.error);
        }

        updateNotifications(responseData.data);

    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

function updateNotifications(notifications) {
    notificationsHolder.innerHTML = '';

    notifications.forEach((notification) => {
        const notificationCard = document.createElement('div');
        notificationCard.className = 'notification-card';

        const notificationContent = document.createElement('p');
        notificationContent.innerText = notification.message;

        notificationCard.appendChild(notificationContent);

        notificationsHolder.appendChild(notificationCard);
    });
}

fetchNotifications();

export {
    fetchNotifications,
}