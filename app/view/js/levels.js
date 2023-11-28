function setLevel(userLevel){
    if(userLevel ==  null){
        userLevel = 0;
    }

    var level = {
        name: '',
        icon: `/taskflow/app/view/assets/levels/${userLevel}.png`,
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

    return level;
}

export default setLevel;
