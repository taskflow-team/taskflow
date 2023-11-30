import fetchUserData from '../user/fetchUserData.js';
import { createReward, renameReward } from './rewardsFunctions.js';
import { fetchRewards } from './rewardsFilters.js';

function rewardModal(userID, type, rewardId, rewardName, rewardCost){
    const modalBg = document.createElement('div');
    modalBg.className = 'modal-bg';

    const modal = document.createElement('div');
    modal.className = 'modal create-reward-modal';
    modal.style.display = 'flex';

    const title = document.createElement('h2');
    title.innerText = 'Create a reward';

    const inputName = document.createElement('input');
    inputName.className = 'dark-input';
    inputName.id = 'reward-name-input';
    inputName.setAttribute('type', 'text');
    inputName.setAttribute('placeholder', 'Enter the reward name here');

    const nameLabel = document.createElement('label');
    nameLabel.innerText = 'Reward name';

    const inputCost = document.createElement('input');
    inputCost.className = 'dark-input';
    inputCost.id = 'reward-cost-input';
    inputCost.setAttribute('type', 'number');
    inputCost.setAttribute('placeholder', '0');

    const unitiesLabel = document.createElement('label');
    unitiesLabel.innerText = 'Reward unities';

    const inputUnities = document.createElement('input');
    inputUnities.className = 'dark-input';
    inputUnities.id = 'reward-unities-input';
    inputUnities.setAttribute('type', 'number');
    inputUnities.setAttribute('placeholder', '0');

    const costLabel = document.createElement('label');
    costLabel.innerText = 'Reward cost';

    const submitBtn = document.createElement('button');
    submitBtn.className = 'btn btn success';
    submitBtn.innerText = type == 'create' ? 'Create' : 'Rename';

    if(type == 'create'){
        submitBtn.addEventListener('click', () => {
            window.confirm('Are you sure you want to create this reward?') ? createReward(userID) : null;
        });
    } else {
        inputName.value = rewardName;
        inputCost.value = rewardCost;
        submitBtn.addEventListener('click', () => {
            window.confirm('Are you sure you want to rename this reward?') ? renameReward(rewardId) : null;
        });
    }

    const closeIcon = document.createElement('i');
    closeIcon.className = 'fa-solid fa-x';
    closeIcon.addEventListener('click', closeModal);

    modal.appendChild(title);
    modal.appendChild(nameLabel);
    modal.appendChild(inputName);
    modal.appendChild(unitiesLabel);
    modal.appendChild(inputUnities);
    modal.appendChild(costLabel);
    modal.appendChild(inputCost);
    modal.appendChild(submitBtn);
    modal.appendChild(closeIcon);

    document.body.appendChild(modalBg);
    document.body.appendChild(modal);
}

function closeModal(){
    let modalBg = document.querySelector('.modal-bg');
    let modal = document.querySelector('.modal');

    modal.className = 'modal create-list-modal close-animation';

    setTimeout(() => {
        modalBg.remove();
        modal.remove();
    }, 1000);
}

const user = await fetchUserData();
const addRewardBtn = document.querySelector('#addRewardBtn');
addRewardBtn.addEventListener('click', () => rewardModal( user.id, 'create'));

export {
    rewardModal,
    closeModal,
}
