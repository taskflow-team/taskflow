<?php

ini_set('display_erros', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../model/Reward.php");
require_once(__DIR__ . "/../dao/RewardDAO.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../dao/GrupoDAO.php");
require_once(__DIR__ . "/../service/RewardService.php");
require_once(__DIR__ . "/Controller.php");

class RewardController extends Controller
{

    private RewardDAO $rewardDao;
    private RewardService $rewardService;
    private UsuarioDAO $usuarioDao;
    private GrupoDAO $grupoDao;

    public function __construct()
    {
        $this->rewardDao = new RewardDAO();
        $this->rewardService = new RewardService();
        $this->usuarioDao = new UsuarioDAO();
        $this->grupoDao = new GrupoDAO();

        $this->setActionDefault("list");

        $this->handleAction();
    }

    protected function create()
    {
        $dados["id"] = 0;
        $this->loadView("home/form.php", $dados, "", "");
    }

    protected function claimReward()
    {
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true);
    
        if ($requestData === null) {
            $response = array(
                'message' => 'Invalid JSON data',
            );
    
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    
        $userID = $requestData['userID'];
        $rewardID = $requestData['rewardID'];
        $groupID = isset($requestData['groupID']) ? $requestData['groupID'] : null;
    
        $reward = $this->rewardDao->findRewardById($rewardID);
    
        $claimed_times = $reward->getClaimed_times();
        $reward_unities = $reward->getRewardUnities();
    
        if ($reward_unities <= 0) {
            $response = array(
                'ok' => false,
                'error' => 'This reward does not have more unities to claim.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    
        $reward->setRewardUnities($reward_unities - 1);
        $reward->setClaimed_times($claimed_times + 1);
    
        try {
            $this->rewardDao->updateReward($reward);
    
            $rewardCost = $reward->getRewardCost();
    
            if ($groupID == null) {
                $user = $this->usuarioDao->findById($userID);
                $userPoints = $user->getPontos();
                $newPoints = $userPoints - $rewardCost; 
                $user->setPontos($newPoints);
                $this->usuarioDao->update($user);
            } else {
                $groupUserPoints = $this->grupoDao->getGroupUserPoints($groupID, $userID);
                $newPoints = $groupUserPoints - $rewardCost; 
                $this->grupoDao->updateGroupUserPoints($groupID, $userID, $newPoints);
            }
    
            $response = array(
                'ok' => true,
                'message' => 'Reward claimed successfully.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } catch (PDOException $e) {
            $response = array(
                'ok' => false,
                'message' => 'An error occurred during the request',
                'error' => $e->getMessage()
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    protected function listGroup()
    {
        if (!$this->usuarioLogado()) {
            exit;
        }

        $rule = $_GET['rule'];
        $groupId = $_GET['groupId'];

        $rewards = $this->rewardDao->findAllRewardsByGroupId($groupId, $rule);

        $response = array(
            'message' => 'Success',
            'data' => array_map(function ($reward) {
                return (object) array(
                    'id_reward' => $reward->getIdReward(),
                    'reward_name' => $reward->getRewardName(),
                    'reward_cost' => $reward->getRewardCost(),
                    'id_user' => $reward->getIdUser(),
                    'id_group' => $reward->getIdGroup(),
                    'reward_unities' => $reward->getRewardUnities(),
                    'claimed_times' => $reward->getClaimed_times(),
                );
            }, $rewards)
        );

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    protected function list()
    {
        if (!$this->usuarioLogado()) {
            exit;
        }

        $userID = $_SESSION[SESSAO_USUARIO_ID];
        $rule = $_GET['rule'];

        $rewards = $this->rewardDao->findAllRewards($userID, $rule);

        $response = array(
            'message' => 'Success',
            'data' => array_map(function ($reward) {
                return (object) array(
                    'id_reward' => $reward->getIdReward(),
                    'reward_name' => $reward->getRewardName(),
                    'reward_cost' => $reward->getRewardCost(),
                    'id_user' => $reward->getIdUser(),
                    'id_group' => $reward->getIdGroup(),
                    'reward_unities' => $reward->getRewardUnities(),
                    'claimed_times' => $reward->getClaimed_times(),
                );
            }, $rewards)
        );

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    protected function edit()
    {
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true);

        if ($requestData === null) {
            $response = array(
                'message' => 'Invalid JSON data',
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $formData = $requestData['formData'];
        $rewardId = $requestData['rewardId'];

        $reward = $this->rewardDao->findRewardById($rewardId);

        $reward->setRewardName($formData['reward_name']);
        $reward->setRewardCost($formData['reward_cost']);
        $reward->setIdUser($formData['id_user']);
        $reward->setIdGroup($formData['id_group']);

        try {
            $this->rewardDao->updateReward($reward);

            $response = array(
                'ok' => true,
                'message' => 'Reward updated successfully.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } catch (PDOException $e) {
            $response = array(
                'ok' => false,
                'message' => 'An error occurred during the request',
                'error' => $e->getMessage()
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    protected function save()
    {
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true);

        if ($requestData === null) {
            $response = array(
                'message' => 'Invalid JSON data',
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $rewardName = $requestData['rewardName'];
        $rewardCost = $requestData['rewardCost'];
        $rewardUnities = $requestData['rewardUnities'];
        $userID = $requestData['userID'];
        $groupID = isset($requestData['groupID']) ? $requestData['groupID'] : null;

        $reward = new Reward();
        $reward->setRewardName($rewardName);
        $reward->setRewardCost($rewardCost);
        $reward->setRewardUnities($rewardUnities);
        $reward->setIdUser($userID);
        $reward->setIdGroup($groupID);

        $erros = $this->rewardService->validarDados($reward);

        if (empty($erros)) {
            try {
                $this->rewardDao->insertReward($reward);

                $response = array(
                    'ok' => true,
                    'message' => 'Reward saved successfully.'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } catch (PDOException $e) {
                $response = array(
                    'ok' => false,
                    'message' => 'An error occurred during the request',
                    'error' => $e->getMessage()
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            $response = array(
                'ok' => false,
                'message' => 'Validation erros',
                'erros' => $erros
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    protected function delete()
    {
        if (!$this->usuarioLogado()) {
            exit;
        }

        $rewardID = $_GET['rewardID'];

        $this->rewardDao->deleteReward($rewardID);

        $response = array(
            'message' => 'Reward deleted successfully.',
        );

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

$rewardCont = new RewardController();
