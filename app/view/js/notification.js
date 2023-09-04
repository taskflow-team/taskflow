function closeNotification(){
    let currentNotification = document.querySelector('.notification');
    document.body.removeChild(currentNotification);
}

function notificate(type, title, content){
    const htmlTitle = document.createElement('h3');
    htmlTitle.innerText = title;

    const htmlContent = document.createElement('span');
    htmlContent.innerText = content;

    const closeBtn = document.createElement('button');
    closeBtn.setAttribute('class', 'closeBtn');
    closeBtn.innerText = 'X';
    closeBtn.addEventListener('click', closeNotification);

    const notification = document.createElement('div');

    if(type == 'success'){
        notification.className =  'notification success';
    } else if(type == 'error'){
        notification.className = 'notification error';
    } else if(type == 'warning') {
        notification.className = 'notification warning';
        closeBtn.style.color = '#171717';
    }

    notification.appendChild(htmlTitle);
    notification.appendChild(htmlContent);
    notification.appendChild(closeBtn);

    document.body.appendChild(notification);
}

export default notificate;
