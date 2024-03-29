<?php
#Nome do arquivo: RewardDAO.php
#Objetivo: classe DAO para o model de Reward

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../configs/Connection.php");
include_once(__DIR__ . "/../model/Reward.php");

class RewardDAO
{
    public function findAllRewards($idUsuario, $rule)
    {
        $conn = Connection::getConn();
    
        if ($rule == "available") {
            $sql = "SELECT * FROM tb_rewards r WHERE r.id_user = ? AND reward_unities > 0 AND r.id_group IS NULL";
        } 
        elseif ($rule == "unavailable") {
            $sql = "SELECT * FROM tb_rewards r WHERE r.id_user = ? AND reward_unities = 0 AND r.id_group IS NULL";
        } 
        else {
            $sql = "SELECT * FROM tb_rewards r WHERE r.id_user = ? AND r.id_group IS NULL";
        }
    
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $idUsuario);
    
        $stm->execute();
        $result = $stm->fetchAll();
    
        return $this->mapRewards($result);
    }
    

    public function findAllRewardsByGroupId($groupId, $rule)
    {
        $conn = Connection::getConn();

        if($rule == "available")
        {
            $sql = "SELECT * FROM tb_rewards r WHERE r.id_group = ? AND reward_unities > 0";
        }   
        else if($rule == "unavailable")
        {
            $rule = 0;
            $sql = "SELECT * FROM tb_rewards r WHERE r.id_group = ? AND reward_unities = " . $rule;
        }
        else
        {
            $sql = "SELECT * FROM tb_rewards r WHERE r.id_group = ?";
        }
        $stm = $conn->prepare($sql);
        $stm->execute([$groupId]);
        $result = $stm->fetchAll();

        return $this->mapRewards($result);
    }

    public function findRewardById($idReward)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_rewards r WHERE r.id_reward = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$idReward]);
        $result = $stm->fetchAll();

        $rewards = $this->mapRewards($result);

        if (count($rewards) == 1)
            return $rewards[0];
        elseif (count($rewards) == 0)
            return null;

        die("RewardDAO.findRewardById() - Error: more than one reward found.");
    }

    public function insertReward(Reward $reward)
    {
        $conn = Connection::getConn();

        $sql = "INSERT INTO tb_rewards (reward_name, reward_cost, id_user, id_group, reward_unities, claimed_times)" .
            " VALUES (:reward_name, :reward_cost, :id_user, :id_group, :reward_unities, :claimed_times)";

        $stm = $conn->prepare($sql);
        $stm->bindValue(":reward_name", $reward->getRewardName());
        $stm->bindValue(":reward_cost", $reward->getRewardCost(), PDO::PARAM_INT);
        $stm->bindValue(":id_user", $reward->getIdUser());
        $stm->bindValue(":id_group", $reward->getIdGroup());
        $stm->bindValue(":reward_unities", $reward->getRewardUnities());
        $stm->bindValue(":claimed_times", $reward->getClaimed_times());
        $stm->execute();
    }

    public function updateReward(Reward $reward)
    {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_rewards SET reward_name = ?, reward_cost = ?, id_user = ?, id_group = ?, reward_unities = ?, claimed_times = ? WHERE id_reward = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $reward->getRewardName());
        $stm->bindValue(2, $reward->getRewardCost(), PDO::PARAM_INT);
        $stm->bindValue(3, $reward->getIdUser());
        $stm->bindValue(4, $reward->getIdGroup());
        $stm->bindValue(5, $reward->getRewardUnities());
        $stm->bindValue(6, $reward->getClaimed_times());
        $stm->bindValue(7, $reward->getIdReward());
        $stm->execute();
    }

    public function deleteReward($idReward)
    {
        $conn = Connection::getConn();

        $sql = "DELETE FROM tb_rewards WHERE id_reward = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $idReward);
        $stm->execute();
    }

    private function mapRewards($result)
    {
        $rewards = array();

        foreach ($result as $row) {
            $reward = new Reward();
            $reward->setIdReward($row["id_reward"]);
            $reward->setRewardName($row["reward_name"]);
            $reward->setRewardCost($row["reward_cost"]);
            $reward->setIdUser($row["id_user"]);
            $reward->setIdGroup($row["id_group"]);
            $reward->setRewardUnities($row["reward_unities"]);
            $reward->setClaimed_times($row["claimed_times"]);
            array_push($rewards, $reward);
        }

        return $rewards;
    }
}
