import { fetchRewards, updateUserData } from "./groupRewardsFilters.js";
import { closeModal } from "./groupRewardsModal.js";
import notificate from "../../notification.js";

async function createReward(userID){
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

        const response = await fetch(BASE_URL + '/controller/RewardController.php?action=save', reqConfigs);
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

async function renameReward(rewardID){
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
            throw new Error('Failed to rename List');
        }

        closeModal();
        fetchRewards();
        updateUserData();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function deleteReward(event){
    const deleteBtn = event.target;
    const rewardID = deleteBtn.dataset.id;

    deleteBtn.style.padding = '0';

    deleteBtn.innerText = '';

    let confirmBtn = document.createElement('span');
    confirmBtn.innerText = 'Confirm';
    confirmBtn.className = 'confirm-btn';
    confirmBtn.addEventListener('click', async() => {
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
    })

    let cancelBtn = document.createElement('span');
    cancelBtn.innerText = 'Cancel';
    cancelBtn.className = 'cancel-btn';

    deleteBtn.appendChild(confirmBtn);
    deleteBtn.appendChild(cancelBtn);

    cancelBtn.addEventListener('click', () => {
        deleteBtn.innerHtml = '';
        deleteBtn.innerText = 'Delete';
        deleteBtn.style.padding = '0 25px 10px 25px';
    });
}

async function claimReward(event, user, reward){
    const element = event.target;

    if(reward.reward_cost > user.pontos){
        notificate('warning', 'Warning', "You don't have enough emeralds, please complete more tasks");
        return;
    }

    let confirmBtn = document.createElement('span');
    confirmBtn.innerText = 'Confirm';
    confirmBtn.className = 'confirm-btn';
    confirmBtn.addEventListener('click', async() => {
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

            const response = await fetch(BASE_URL + '/controller/RewardController.php?action=claimReward', reqConfigs);
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
    })

    let cancelBtn = document.createElement('span');
    cancelBtn.innerText = 'Cancel';
    cancelBtn.className = 'cancel-btn';
    cancelBtn.addEventListener('click', () => {
        element.innerHtml = '';
        element.innerText = 'Claim';
    });

    element.innerText = '';
    element.innerHtml = '';
    element.appendChild(confirmBtn);
    element.appendChild(cancelBtn);
}



export {
    deleteReward,
    createReward,
    renameReward,
    claimReward
}
