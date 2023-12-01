const userId = document.getElementById('idUsuario').value;

async function checkUnreadNotifications() {
    try {
        const response = await fetch(BASE_URL + '/controller/NotificacaoController.php?action=countUnread&userId=' + userId);
        const data = await response.json();

        const notifHolder = document.querySelector('.nav-icon-holder');
        let notifCounter = document.getElementById('unreadNotificationsCount');

        if (data.ok && data.unreadCount > 0) {
            if (!notifCounter) {
                notifCounter = document.createElement('span');
                notifCounter.id = 'unreadNotificationsCount';
                notifHolder.appendChild(notifCounter);
            }
            notifCounter.innerText = data.unreadCount;
        } else {
            if (notifCounter) {
                notifCounter.remove();
            }
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
