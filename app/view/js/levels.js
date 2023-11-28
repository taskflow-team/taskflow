function setLevel(userLevel){
    const levelHolder = document.createElement('span');
    

    switch (parseInt(userLevel)) {
        case 0:
            return 'Novice';
            break;
        case 1:
            return 'Apprentice';
            break;
        case 2:
            return 'Journeyman';
            break;
        case 3:
            return 'Expert';
            break;
        case 4:
            return 'Master';
            break;
        case 5:
            return 'Emerald Knight';
            break;
        default:
            return 'Novice';
            break;
    }
}

export default setLevel;
