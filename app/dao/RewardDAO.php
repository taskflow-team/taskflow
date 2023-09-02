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
    public function findAllRewards($idUsuario)
    {
        $conn = Connection::getConn();

        $sql = "SELECT * FROM tb_rewards r WHERE r.id_user = ?";
        $stm = $conn->prepare($sql);
        $stm->execute([$idUsuario]);
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

        $sql = "INSERT INTO tb_rewards (reward_name, reward_cost, id_user, id_group, reward_owned)" .
            " VALUES (:reward_name, :reward_cost, :id_user, :id_group, :reward_owned)";

        $stm = $conn->prepare($sql);
        $stm->bindValue(":reward_name", $reward->getRewardName());
        $stm->bindValue(":reward_cost", $reward->getRewardCost(), PDO::PARAM_INT);
        $stm->bindValue(":id_user", $reward->getIdUser());
        $stm->bindValue(":id_group", $reward->getIdGroup());
        $stm->bindValue(":reward_owned", $reward->getRewardOwned());
        $stm->execute();
    }

    public function updateReward(Reward $reward)
    {
        $conn = Connection::getConn();

        $sql = "UPDATE tb_rewards SET reward_name = ?, reward_cost = ?, id_user = ?, id_group = ?, reward_owned = ? WHERE id_reward = ?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1, $reward->getRewardName());
        $stm->bindValue(2, $reward->getRewardCost(), PDO::PARAM_INT);
        $stm->bindValue(3, $reward->getIdUser());
        $stm->bindValue(4, $reward->getIdGroup());
        $stm->bindValue(5, $reward->getRewardOwned());
        $stm->bindValue(6, $reward->getIdReward());
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
            $reward->setRewardOwned($row["reward_owned"]);
            array_push($rewards, $reward);
        }

        return $rewards;
    }
}
