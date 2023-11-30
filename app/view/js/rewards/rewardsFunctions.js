import { fetchRewards, updateUserData } from "./rewardsFilters.js";
import { closeModal } from "./rewardsModal.js";
import notificate from "../notification.js";

async function createReward(userID) {
    let rewardName = document.querySelector('#reward-name-input').value;
    let rewardCost = document.querySelector('#reward-cost-input').value;
    let rewardUnities = document.querySelector('#reward-unities-input').value;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                rewardName: rewardName,
                rewardCost: rewardCost,
                rewardUnities: rewardUnities,
                userID: userID
            })
        };

        const response = await fetch('RewardController.php?action=save', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to create List');
        }

        closeModal();
        fetchRewards();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function renameReward(rewardID) {
    let rewardName = document.querySelector('#reward-name-input').value;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                rewardID: rewardID,
                rewardName: rewardName
            })
        };

        const response = await fetch('ListaController.php?action=rename', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to rename List');
        }

        closeModal();
        fetchRewards();
        updateUserData();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function deleteReward(event) {
    const deleteBtn = event.target;
    const rewardID = deleteBtn.dataset.id;

    try {
        const response = await fetch(`RewardController.php?action=delete&rewardID=${rewardID}`, {
            method: "DELETE",
        });

        if (!response.ok) {
            throw new Error('The request to the server has failed');
        }

        fetchRewards();

    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function claimReward(user, reward) {
    if (reward.reward_cost > user.pontos) {
        notificate('warning', 'Warning', "You don't have enough emeralds, please complete more tasks");
        return;
    }

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                userID: user.id,
                rewardID: reward.id_reward
            })
        };

        const response = await fetch('RewardController.php?action=claimReward', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        updateUserData();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}



export {
    deleteReward,
    createReward,
    renameReward,
    claimReward
}
