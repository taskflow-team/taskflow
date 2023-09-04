import { deleteReward, claimReward } from "./rewardsFunctions.js";
import notificate from "../notification.js";
import fetchUserData from "../user/fetchUserData.js";
let user;

async function updateUserData() {
    user = await fetchUserData();

    const emeraldsHolder = document.querySelector('#emeralds-holder');
    emeraldsHolder.innerText = user.pontos;

    fetchRewards();
}

updateUserData();

async function fetchRewards(){
    try {
        const response = await fetch('RewardController.php?action=list');
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            throw new Error(responseData.error);
        }

        updateRewards(responseData.data);

    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

function updateRewards(rewards) {
    const rewardsHolder = document.querySelector('.rewards-holder');
    rewardsHolder.innerHTML = '';

    rewards.forEach((reward) => {
        const {
            id_reward,
            reward_name,
            reward_cost,
            id_user,
            id_group,
            reward_owned
        } = reward;

        const rewardCard = document.createElement('div');
        rewardCard.className = 'reward-card';

        const defaultContent = document.createElement('div');
        defaultContent.className = 'default-content';

        const rewardName = document.createElement('span');
        rewardName.innerText = reward.reward_name;

        const costDiv = document.createElement('div');
        costDiv.className = 'cost';
        costDiv.innerHTML = reward.reward_cost + ' <img src="' + BASE_URL + '/view/assets/icons/emerald.png" alt="Emerald icon">';

        defaultContent.appendChild(rewardName);
        defaultContent.appendChild(costDiv);

        const functionsDiv = document.createElement('div');
        functionsDiv.className = 'rewards-functions functions-show';

        const claimBtn = document.createElement('button')
        claimBtn.id = 'claimBtn';
        claimBtn.innerText = 'Claim';
        claimBtn.addEventListener('click', (event) => claimReward(event, user, reward));

        functionsDiv.appendChild(claimBtn);

        const deleteBtn = document.createElement('button');
        deleteBtn.id = 'deleteBtn';
        deleteBtn.dataset.id = id_reward;
        deleteBtn.innerText = 'Delete';
        deleteBtn.addEventListener('click', deleteReward);
        
        functionsDiv.appendChild(deleteBtn);

        rewardCard.appendChild(defaultContent);
        rewardCard.appendChild(functionsDiv);

        rewardsHolder.appendChild(rewardCard);

    });
}

export {
    fetchRewards,
    updateUserData
}
