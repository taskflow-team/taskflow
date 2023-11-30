const userId = document.getElementById('idUsuario').value;

async function checkUnreadNotifications() {
    try {
        const response = await fetch(BASE_URL + '/controller/NotificacaoController.php?action=countUnread&userId=' + userId);
        const data = await response.json();

        if (data.ok && data.unreadCount > 0) {
            const notifHolder = document.querySelector('.nav-icon-holder');

            const notifCounter = document.createElement('span');
            notifCounter.id = 'unreadNotificationsCount';
            notifCounter.innerText = data.unreadCount;

            notifHolder.appendChild(notifCounter);
        }

    } catch (error) {
        console.error('Error fetching unread notifications:', error);
    }
}

checkUnreadNotifications();
setInterval(checkUnreadNotifications, 5000);

export {
    checkUnreadNotifications
}
