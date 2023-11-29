const userId = document.getElementById('idUsuario').value;

async function checkUnreadNotifications() {
    try {
        const response = await fetch(BASE_URL + '/controller/NotificacaoController.php?action=countUnread&userId=' + userId);
        const data = await response.json();

        if (data.ok && data.unreadCount > 0) {
            document.getElementById('unreadNotificationsCount').innerText = data.unreadCount;
        } else {
            document.getElementById('unreadNotificationsCount').innerText = '';
        }
    } catch (error) {
        console.error('Error fetching unread notifications:', error);
    }
}

checkUnreadNotifications();
setInterval(checkUnreadNotifications, 5000);
