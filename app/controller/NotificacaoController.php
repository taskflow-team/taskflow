<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary models, DAOs, and services
require_once(__DIR__ . "/../model/Notificacao.php");
require_once(__DIR__ . "/../dao/NotificacaoDAO.php");
require_once(__DIR__ . "/Controller.php");

class NotificacaoController extends Controller
{
    private NotificacaoDAO $notificacaoDao;

    public function __construct()
    {
        $this->notificacaoDao = new NotificacaoDAO();

        $this->setActionDefault("create");

        $this->handleAction();
    }

    protected function create()
    {
        $dados["id_notification"] = 0;
        $this->loadView("pages/notification/notification.php", $dados, "", "");
    }

    protected function list()
    {
        $userId = $_GET['userId'];
    
        try {
            $notifications = $this->notificacaoDao->listNotifications($userId);
    
            $response = array(
                'ok' => true,
                'data' => array_map(function ($notification) {
                    return (object) array(
                        'id_notification' => $notification->getId_notification(),
                        'type' => $notification->getType(),
                        'message' => $notification->getMessage(),
                        'date_created' => $notification->getDate_created(),
                        'id_user' => $notification->getId_user(),
                        'id_group' => $notification->getId_group(),
                        'is_read' => $notification->getIs_read(),
                    );
                }, $notifications)
            );
    
            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
}

// Create an instance of the controller
$notificationCont = new NotificacaoController();
