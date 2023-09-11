<?php
    
class Reward
{
    private $id_reward;
    private $reward_name;
    private $reward_cost;
    private $id_user;
    private $id_group;
    private $reward_unities;
    private $claimed_times;

    public function getClaimed_times()
    {
        return $this->claimed_times;
    }

    public function setClaimed_times($claimed_times)
    {
        $this->claimed_times = $claimed_times;

        return $this;
    }

    public function getRewardUnities()
    {
        return $this->reward_unities;
    }

    public function setRewardUnities($reward_unities)
    {
        $this->reward_unities = $reward_unities;
    }

    public function getIdReward()
    {
        return $this->id_reward;
    }

    public function setIdReward($id_reward)
    {
        $this->id_reward = $id_reward;
    }

    public function getRewardName()
    {
        return $this->reward_name;
    }

    public function setRewardName($reward_name)
    {
        $this->reward_name = $reward_name;
    }

    public function getRewardCost()
    {
        return $this->reward_cost;
    }

    public function setRewardCost($reward_cost)
    {
        $this->reward_cost = $reward_cost;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getIdGroup()
    {
        return $this->id_group;
    }

    public function setIdGroup($id_group)
    {
        $this->id_group = $id_group;
    }
}