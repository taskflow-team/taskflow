import { fetchUserData } from "./rewardFilters.js";
import { closeModal, showRewardsBar } from "./rewardModal.js";
import notificate from "../notification.js";

async function claimReward(userID, rewardID){
    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                userID: userID,
                rewardID: rewardID
            })
        };

        const response = await fetch('RewardController.php?action=claimReward', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to create Reward');
        }

        fetchUserData();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function createReward(userID){
    let rewardName = document.querySelector('#reward-name-input').value;
    let rewardCost = document.querySelector('#reward-cost-input').value;

    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                rewardName: rewardName,
                rewardCost: rewardCost,
                userID: userID
            })
        };

        const response = await fetch('RewardController.php?action=save', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to create List');
        }

        closeModal();
        fetchUserData();
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

        const response = await fetch('ListaController.php?action=rename', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404 || !responseData.ok) {
            throw new Error('Failed to create List');
        }

        closeModal();
        fetchUserData();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

async function deleteReward(event){
    const deleteBtn = event.target;
    const rewardID = deleteBtn.dataset.id;

    deleteBtn.style.padding = '0';

    deleteBtn.innerText = '';

    const confirmBtn = document.createElement('span');
    confirmBtn.innerText = 'Confirm';
    confirmBtn.className = 'confirm-btn';
    confirmBtn.addEventListener('click', async() => {
        deleteBtn.innerHtml = '';
        deleteBtn.innerText = '';
        console.log(rewardID);
        try {
            const response = await fetch(`RewardController.php?action=delete&rewardID=${rewardID}`, {
                method: "DELETE",
            });

            if (!response.ok) {
                throw new Error('The request to the server has failed');
            }

            const responseData =  await response.json();
            notificate('success', 'Success', responseData.message)

            // Atualiza a tela com as recompensas atuais
            fetchUserData();

        } catch (error) {
            notificate('error', 'Error', error.message);
        }
    })

    const cancelBtn = document.createElement('span');
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

export {
    deleteReward,
    createReward,
    renameReward,
    claimReward
}
