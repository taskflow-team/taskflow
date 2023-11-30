import { deleteReward, claimReward } from "./groupRewardsFunctions.js";
import notificate from "../../notification.js";

let user;
const availableBtn = document.querySelector('.btn-filter-available');
const unavailableBtn = document.querySelector('.btn-filter-unavailable');

async function fetchUserGroupPoints() {
    const reqConfigs = {
        method: "GET",
        headers: {
            'Content-type': 'application/json'
        },
    };

    try {
        const response = await fetch(BASE_URL + `/controller/GrupoController.php?action=getUserGroupPoints&groupId=${GROUP_ID}`, reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            notificate('error', 'Erro', responseData.error);
        }

        return responseData.points;

    } catch (error) {
        notificate('error', 'Error', error);
    }
}

async function updateUserGroupPoints() {
    const points = await fetchUserGroupPoints();

    const pointsHolder = document.querySelector('#emeralds-holder');
    pointsHolder.innerText = points;

    fetchRewards();
}

updateUserGroupPoints();

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
        const response = await fetch(BASE_URL + '/controller/RewardController.php?action=listGroup&rule=' + rule + `&groupId=${GROUP_ID}`, reqConfigs);
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
    if(IS_ADMIN == 0)
    {
        const rewardCard = document.querySelector('#addRewardBtn');
        rewardCard.style.display = 'none';
    }

    const rewardsHolder = document.querySelector('.rewards-holder-group');
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
        rewardCard.className = 'reward-card group-reward';

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

        const points = document.querySelector('#emeralds-holder').innerText;

        const claimBtn = document.createElement('button')
        claimBtn.id = 'claimBtn';
        claimBtn.innerText = 'Claim';
        claimBtn.addEventListener('click', (event) => {
            window.confirm('Are you sure you want to claim this reward?') ? claimReward(points, reward) : null;
        });

        functionsDiv.appendChild(claimBtn);

        if(IS_ADMIN == 1)
        {
            const deleteBtn = document.createElement('button');
            deleteBtn.id = 'deleteBtn';
            deleteBtn.dataset.id = id_reward;
            deleteBtn.innerText = 'Delete';
            deleteBtn.addEventListener('click', (event) => {
                window.confirm('Are you sure you want to delete this reward?') ? deleteReward(event) : null;
            });

            functionsDiv.appendChild(deleteBtn);
        }

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
    updateUserGroupPoints
}
