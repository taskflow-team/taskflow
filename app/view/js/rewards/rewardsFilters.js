import { deleteReward, claimReward } from "./rewardsFunctions.js";
import notificate from "../notification.js";
import fetchUserData from "../user/fetchUserData.js";

let user;
const availableBtn = document.querySelector('.btn-filter-available');
const unavailableBtn = document.querySelector('.btn-filter-unavailable');

async function updateUserData() {
    user = await fetchUserData();

    const emeraldsHolder = document.querySelector('#emeralds-holder');
    emeraldsHolder.innerText = user.pontos;

    fetchRewards();
}

updateUserData();

async function fetchRewards(){
    let selectedRule = 0;

    let availableBtnRule = availableBtn.classList.contains("active") ? 1 : 2;
    let unavailableBtnRule = unavailableBtn.classList.contains("active") ? 1 : 2;

    if(availableBtnRule == 1 && unavailableBtnRule == 2)
    {  
        selectedRule = 1;
    }
    else if(availableBtnRule == 2 && unavailableBtnRule == 1)
    {
        selectedRule = 2;
    }
    else
    {
        selectedRule = 0;
    }

    let rule = '';

    if (selectedRule == 1 ) {
        rule =  'available';
    } else if(selectedRule == 2) {
        rule = 'unavailable';
    } else {
        rule = '';
    }

    const reqConfigs = {
        method: "GET",
        headers: {
            'Content-type': 'application/json'
        },
    };

    try {
        const response = await fetch('RewardController.php?action=list&rule=' + rule, reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        updateRewards(responseData.data);

    } catch (error) {
        notificate('error', 'Error', error);
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
        claimBtn.addEventListener('click', (event) => {
            window.confirm('Are you sure you want to claim this reward?') ? claimReward(user, reward) : null;
        });

        functionsDiv.appendChild(claimBtn);

        const deleteBtn = document.createElement('button');
        deleteBtn.id = 'deleteBtn';
        deleteBtn.dataset.id = id_reward;
        deleteBtn.innerText = 'Delete';
        deleteBtn.addEventListener('click', (event) => {
            window.confirm('Are you sure you want to delete this reward?') ? deleteReward(event) : null;
        });
        
        functionsDiv.appendChild(deleteBtn);

        rewardCard.appendChild(defaultContent);
        rewardCard.appendChild(functionsDiv);

        rewardsHolder.appendChild(rewardCard);
    });
}

availableBtn.addEventListener('click', function(){
    if(availableBtn.classList.contains("active"))
    {
        availableBtn.classList.remove("active");
    }
    else
    {
        availableBtn.classList.add("active");
        unavailableBtn.classList.remove("active");
    }
    fetchRewards();
});

unavailableBtn.addEventListener('click', function(){
    if(unavailableBtn.classList.contains("active"))
    {
        unavailableBtn.classList.remove("active");
    }
    else
    {
        unavailableBtn.classList.add("active");
        availableBtn.classList.remove("active");
    }
    fetchRewards();
});

export {
    fetchRewards,
    updateUserData
}
