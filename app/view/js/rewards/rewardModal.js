import { createReward, renameReward } from './rewardFunctions.js';

const rewardsBtn = document.querySelector('#rewardsBtn');

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

function showRewardsBar(){
    let pseudoBody = document.querySelector('.pseudo-body');
    let listsHolder = document.querySelector('.lists-holder');
    let rewardsBar = document.querySelector('.rewards-bar');

    if(rewardsBar.classList.contains('rewards-show')){
        rewardsBar.className = 'rewards-bar rewards-hidden';
        pseudoBody.style.right = '0';
        listsHolder.style.gridTemplateColumns = 'repeat(4, 1fr)';
        return;
    }

    rewardsBar.className = 'rewards-bar rewards-show';
    pseudoBody.style.right = '300px';
    listsHolder.style.gridTemplateColumns = 'repeat(3, 1fr)';

}

rewardsBtn.addEventListener('click', showRewardsBar);

export {
    rewardModal,
    closeModal,
    showRewardsBar
}
