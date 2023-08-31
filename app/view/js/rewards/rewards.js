const rewardsBtn = document.querySelector('#rewardsBtn');

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
