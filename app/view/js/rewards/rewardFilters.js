import { deleteReward, claimReward } from "./rewardFunctions.js";
import { rewardModal } from "./rewardModal.js";
import notificate from "../notification.js";

const rewardsHolder = document.querySelector('.rewards-holder');
let userPoints;
let userID;

// Função para obter os dados do usuário do servidor
async function fetchUserData() {
    const reqConfigs = {
        method: "GET",
        headers: {
            'Content-type': 'application/json'
        },
    };

    try {
        const response = await fetch(BASE_URL + '/controller/UsuarioController.php?action=getUserData', reqConfigs);
        const responseData = await response.json();

        if (!response.ok || response.status == 404) {
            notificate(
                'error',
                'Erro',
                responseData.error
            );
        }

        updateUserData(responseData.user);

    } catch (error) {
        notificate('error', 'Error', error);
    }
}

fetchUserData();

function updateUserData(user) {
    const {
        id,
        nome,
        email,
        pontos,
        nivel,
        tarefas_concluidas
    } = user;

    const emeraldsHolder = document.querySelector('#emeralds-holder');
    emeraldsHolder.innerText = pontos;

    userID = id;
    userPoints = pontos;
    fetchRewards();
}

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
    rewardsHolder.innerHTML = '';

    const createRewardButton = document.createElement('button');
    createRewardButton.innerHTML = "Add Reward"
    createRewardButton.addEventListener('click', () => rewardModal(userID, "create"));
    rewardsHolder.appendChild(createRewardButton);

    rewards.forEach((reward) => {
        const {
            id_reward,
            reward_name,
            reward_cost,
            id_user,
            id_group,
            reward_owned
        } = reward;

        if(reward_owned != null && reward_owned != 0)
        {
            const rewardCard = document.createElement('div');
            rewardCard.className = 'reward-card';

            const defaultContent = document.createElement('div');
            defaultContent.className = 'default-content';

            const rewardName = document.createElement('span');
            rewardName.innerText = reward.reward_name + " POSSUIDO";

            const functionsDiv = document.createElement('div');
            functionsDiv.className = 'rewards-functions functions-show';

            const deleteBtn = document.createElement('button');
            deleteBtn.id = 'deleteBtn';
            deleteBtn.dataset.id = id_reward;
            deleteBtn.innerText = 'Delete';
            deleteBtn.addEventListener('click', deleteReward);
            
            functionsDiv.appendChild(deleteBtn);
            
            defaultContent.appendChild(rewardName);

            rewardCard.appendChild(defaultContent);
            rewardCard.appendChild(functionsDiv);

            rewardsHolder.appendChild(rewardCard);
        }
        else{
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

            const canClaim = reward.reward_cost > userPoints ? false : true;

            if(canClaim)
            {
                const claimBtn = document.createElement('button')
                claimBtn.id = 'claimBtn';
                claimBtn.dataset.id = id_reward;
                claimBtn.innerText = 'Claim';
                claimBtn.addEventListener('click', () => claimReward(userID, claimBtn.dataset.id));

                functionsDiv.appendChild(claimBtn);
            }

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
    fetchUserData
}
