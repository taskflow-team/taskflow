import fetchUserData from '../user/fetchUserData.js';
import { createReward, renameReward } from './rewardsFunctions.js';
import { fetchRewards } from './rewardsFilters.js';

function rewardModal(userID, type, rewardId, rewardName, rewardCost){
    const modalBg = document.createElement('div');
    modalBg.className = 'modal-bg';

    const modal = document.createElement('div');
    modal.className = 'modal create-reward-modal';
    modal.style.display = 'flex';

    const inputName = document.createElement('input');
    inputName.className = 'dark-input';
    inputName.id = 'reward-name-input';
    inputName.setAttribute('type', 'text');
    inputName.setAttribute('placeholder', 'Enter the reward name here');

    const inputCost = document.createElement('input');
    inputCost.className = 'dark-input';
    inputCost.id = 'reward-cost-input';
    inputCost.setAttribute('type', 'number');
    inputCost.setAttribute('placeholder', '0');

    const submitBtn = document.createElement('button');
    submitBtn.className = 'btn btn success';
    submitBtn.innerText = type == 'create' ? 'Create' : 'Rename';

    if(type == 'create'){
        submitBtn.addEventListener('click', () => createReward(userID));
    } else {
        inputName.value = rewardName;
        inputCost.value = rewardCost;
        submitBtn.addEventListener('click', () => renameReward(rewardId));
    }

    const closeIcon = document.createElement('i');
    closeIcon.className = 'fa-solid fa-x';
    closeIcon.addEventListener('click', closeModal);

    modal.appendChild(inputName);
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
    }, 500);
}

const user = await fetchUserData();
const addRewardBtn = document.querySelector('#addRewardBtn');
addRewardBtn.addEventListener('click', () => rewardModal( user.id, 'create'));

export {
    rewardModal,
    closeModal,
}
