function calculatePercentage(partial, total){
    let percentage = (partial / total) * 100;
    percentage = percentage.toFixed(2);

    return percentage;
}

function setLevel(userLevel, completedTasks){
    if(userLevel ==  null  || completedTasks == null){
        userLevel = 0;
        completedTasks = 0;
    }

    var level = {
        name: '',
        icon: `/taskflow/app/view/assets/levels/${userLevel}.png`,
        percentageBar: 0,
        ramainingTasks: ''
    };

    switch (parseInt(userLevel)) {
        case 0:
            level.name = 'Novice';
            break;
        case 1:
            level.name = 'Apprentice';
            break;
        case 2:
            level.name = 'Journeyman';
            break;
        case 3:
            level.name = 'Expert';
            break;
        case 4:
            level.name = 'Master';
            break;
        case 5:
            level.name = 'Emerald Knight';
            break;
        default:
            level.name = 'Novice';
            break;
    }

    if (completedTasks < 20) {
        level.percentageBar = calculatePercentage(completedTasks, 20);
        level.remainingTasks = `${completedTasks}/20`;
    } else if (completedTasks < 40) {
        level.percentageBar = calculatePercentage(completedTasks, 40);
        level.remainingTasks = `${completedTasks}/40`;
    } else if (completedTasks < 80) {
        level.percentageBar = calculatePercentage(completedTasks, 80);
        level.remainingTasks = `${completedTasks}/80`;
    } else if (completedTasks < 160) {
        level.percentageBar = calculatePercentage(completedTasks, 160);
        level.remainingTasks = `${completedTasks}/160`;
    } else if (completedTasks < 240) {
        level.percentageBar = calculatePercentage(completedTasks, 240);
        level.remainingTasks = `${completedTasks}/240`;
    }

    return level;
}

export default setLevel;
