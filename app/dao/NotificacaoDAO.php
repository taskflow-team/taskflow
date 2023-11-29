<?php
#Nome do arquivo: NotificacaoDAO.php
#Objetivo: classe DAO para o model de Notificacao

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../configs/Connection.php");
include_once(__DIR__ . "/../model/Notificacao.php");

class NotificacaoDAO
{
    public function countUnreadNotifications($userId) {
        $conn = Connection::getConn();
        $sql = "SELECT COUNT(*) as unreadCount FROM tb_notifications WHERE id_user = :id_user AND is_read = 0";
        $stm = $conn->prepare($sql);
        $stm->bindValue(":id_user", $userId);
        $stm->execute();
        $result = $stm->fetch();
        return $result['unreadCount'] ?? 0;
    }

    // Método para listar as notificações de um usuário
    public function listNotifications($id_user)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_notifications WHERE id_user = :id_user ORDER BY date_created DESC";
        $stm = $conn->prepare($sql);
        $stm->bindValue(":id_user", $id_user);
        $stm->execute();
        $result = $stm->fetchAll();

        return $this->mapNotifications($result);
    }

    // Método para buscar uma notificação por seu ID
    public function findByIdNotification(int $id)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_notifications WHERE id_notification = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$id]);
        $result = $stm->fetchAll();

        $notifications = $this->mapNotifications($result);

        if (count($notifications) == 1)
            return $notifications[0];
        elseif (count($notifications) == 0)
            return null;

        die("NotificacaoDAO.findByIdNotification() - Erro: mais de uma notificação encontrada.");
    }

    // Método para inserir uma notificação
    public function insertNotification(Notificacao $notification)
    {
        $conn = Connection::getConn();

        $sql = "INSERT INTO tb_notifications (type, message, date_created, id_user, id_group, is_read)" .
            " VALUES (:type, :message, :date_created, :id_user, :id_group, :is_read)";

        $stm = $conn->prepare($sql);
        $stm->bindValue(":type", $notification->getType());
        $stm->bindValue(":message", $notification->getMessage());
        $stm->bindValue(":date_created", $notification->getDate_created());
        $stm->bindValue(":id_user", $notification->getId_user());
        $stm->bindValue(":id_group", $notification->getId_group());
        $stm->bindValue(":is_read", $notification->getIs_read());
        $stm->execute();
    }

    // Método para atualizar o status de leitura de uma notificação
    public function updateNotificationReadStatus($id_notification, $is_read)
    {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_notifications SET is_read = ? WHERE id_notification = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $is_read);
        $stm->bindValue(2, $id_notification);
        $stm->execute();
    }

    public function updateAllNotificationsReadStatus($userId, $isRead)
    {
        $conn = Connection::getConn();
        $sql = "UPDATE tb_notifications SET is_read = ? WHERE id_user = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$isRead, $userId]);
    }

    // Método para mapear os dados de Notificação para um array
    private function mapNotifications($result)
    {
        $notifications = array();

        foreach ($result as $row) {
            $notification = new Notificacao();
            $notification->setId_notification($row["id_notification"]);
            $notification->setType($row["type"]);
            $notification->setMessage($row["message"]);
            $notification->setDate_created($row["date_created"]);
            $notification->setId_user($row["id_user"]);
            $notification->setId_group($row["id_group"]);
            $notification->setIs_read($row["is_read"]);
            array_push($notifications, $notification);
        }

        return $notifications;
    }
}
