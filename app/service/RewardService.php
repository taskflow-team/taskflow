<?php

require_once(__DIR__ . "/../model/Reward.php");

class RewardService {
    public function validarDados(Reward $reward) {
        $erros = array();

        // Validar campos vazios
        if (!$reward->getRewardName()) {
            array_push($erros, "O campo [Nome] é obrigatório.");
        }

        if (!$reward->getRewardCost()) {
            array_push($erros, "O campo [Custo] é obrigatório.");
        }

        if (!$reward->getRewardUnities()) {
            array_push($erros, "O campo [Unidades] é obrigatório.");
        }

        return $erros;
    }
}
