import { fetchRewards, updateUserGroupPoints } from "./groupRewardsFilters.js";
import { closeModal } from "./groupRewardsModal.js";
import notificate from "../../notification.js";

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
                groupID: GROUP_ID,
                userID: userID
            })
        };

        const response = await fetch(BASE_URL + '/controller/RewardController.php?action=save', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to create reward.');
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

        const response = await fetch(BASE_URL + '/controller/ListaController.php?action=rename', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to rename reward.');
        }

        closeModal();
        fetchRewards();
        updateUserGroupPoints();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function deleteReward(event) {
    const deleteBtn = event.target;
    const rewardID = deleteBtn.dataset.id;

    deleteBtn.innerHtml = '';
    deleteBtn.innerText = '';
    console.log(rewardID);
    try {
        const response = await fetch(BASE_URL + `/controller/RewardController.php?action=delete&rewardID=${rewardID}`, {
            method: "DELETE",
        });

        if (!response.ok) {
            throw new Error('The request to the server has failed');
        }

        // Atualiza a tela com as recompensas atuais
        fetchRewards();

    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function claimReward(points, reward) {
    const userID = document.querySelector("#idUsuario").value;

    if (reward.reward_cost > points) {
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
                userID: userID,
                groupID: GROUP_ID,
                rewardID: reward.id_reward
            })
        };

        const response = await fetch(BASE_URL + '/controller/RewardController.php?action=claimReward', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        updateUserGroupPoints();
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
