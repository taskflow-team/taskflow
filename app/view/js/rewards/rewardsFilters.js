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
            reward_unities,
            claimed_times
        } = reward;

        if(reward_unities > 0)
        {
            const rewardCard = document.createElement('div');
            rewardCard.className = 'reward-card';

            const defaultContent = document.createElement('div');
            defaultContent.className = 'default-content';

            const rewardName = document.createElement('span');
            rewardName.innerText = reward.reward_name;

            const costDiv = document.createElement('div');
            costDiv.className = 'cost';
            costDiv.innerHTML = reward.reward_cost + ' <img src="' + BASE_URL + '/view/assets/icons/emerald.png" alt="Emerald icon">';

            const col01 = document.createElement('div');
            col01.className = 'reward-col';
            col01.appendChild(rewardName);
            col01.appendChild(costDiv);

            const rewardUnities = document.createElement('span');
            rewardUnities.className = 'reward-unities';
            rewardUnities.innerHTML = reward.reward_unities + "x";

            const claimedTimes = document.createElement('span');
            claimedTimes.className = 'reward-claimed';
            claimedTimes.innerHTML = reward.claimed_times == null ? "Claimed Times: " + 0 : "Claimed Times: " + reward.claimed_times;

            defaultContent.appendChild(rewardUnities);
            defaultContent.appendChild(col01);
            defaultContent.appendChild(claimedTimes);

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
        }
        else
        {
            const rewardCard = document.createElement('div');
            rewardCard.className = 'reward-card';

            const defaultContent = document.createElement('div');
            defaultContent.className = 'default-content';

            const rewardName = document.createElement('span');
            rewardName.innerText = reward.reward_name;

            const costDiv = document.createElement('div');
            costDiv.className = 'cost';
            costDiv.innerHTML = reward.reward_cost + ' <img src="' + BASE_URL + '/view/assets/icons/emerald.png" alt="Emerald icon">';

            const rewardSoldOut = document.createElement('div');
            rewardSoldOut.className = 'rewardSoldOut';
            rewardSoldOut.innerHTML = "rewardSoldOut"

            defaultContent.appendChild(rewardName);
            defaultContent.appendChild(rewardSoldOut);

            const functionsDiv = document.createElement('div');
            functionsDiv.className = 'rewards-functions functions-show';

            const deleteBtn = document.createElement('button');
            deleteBtn.id = 'deleteBtn';
            deleteBtn.dataset.id = id_reward;
            deleteBtn.innerText = 'Delete';
            deleteBtn.addEventListener('click', deleteReward);
            
            functionsDiv.appendChild(deleteBtn);

            rewardCard.appendChild(defaultContent);
            rewardCard.appendChild(functionsDiv);

            rewardsHolder.appendChild(rewardCard);
        }
    });
}

export {
    fetchRewards,
    updateUserData
}
