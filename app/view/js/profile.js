import notificate from './notification.js';
import setLevel from './levels.js';

const nameHolder = document.querySelector('#user-name');
const loginHolder = document.querySelector('#user-login');
const emblemHolder = document.querySelector('#emblem-holder');
const innerProgBar = document.querySelector('.inner-prog-bar');
const remainingTasks = document.querySelector('#remaining-tasks');
const levelName = document.querySelector('#level-name');
const emailInput = document.querySelector('#user-email');
const passwordInput = document.querySelector('#user-password');
const userForm = document.querySelector('#frmEditUsuario');
const idHolder = document.querySelector('#userId');
const profilePicture = document.getElementById('profile-picture');
const imageUploadInput = document.getElementById('profile-image-upload');

profilePicture.addEventListener('click', () => {
    imageUploadInput.click();
});

imageUploadInput.addEventListener('change', handleImageUpload);

async function handleImageUpload() {
    const file = imageUploadInput.files[0];
    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (file && validImageTypes.includes(file.type)) {
        const formData = new FormData();
        formData.append('profileImage', file);
        formData.append('userId', idHolder.value);

        try {
            const response = await fetch("UsuarioController.php?action=uploadProfileImage", {
                method: "POST",
                body: formData,
            });

            const responseData = await response.json();

            if (responseData.ok) {
                profilePicture.src = responseData.imageUrl;
                notificate('success', 'Success', 'Profile image updated successfully');
            } else {
                notificate('error', 'Error', responseData.message);
            }
            getUserData();
        } catch (error) {
            notificate('error', 'Error', error.message);
        }
    } else {
        notificate('error', 'Error', 'Please upload a valid image file (JPEG, PNG, or GIF).');
    }
}

async function getUserData() {
    try {
        const response = await fetch("UsuarioController.php?action=getUserData");
        const responseData = await response.json();

        if (!responseData.ok || response.status === 400) {
            notificate("error", "Error", "There was an error while getting user data");
            return;
        }

        updateUserData(responseData.user);
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

getUserData();

function updateUserData(user){
    const userLevel = setLevel(user.nivel, user.tarefas_concluidas);

    nameHolder.innerText = user.nome;
    loginHolder.innerText = user.login;
    emblemHolder.setAttribute('src', userLevel.icon);
    emblemHolder.setAttribute('alt', userLevel.name);
    innerProgBar.style.width = userLevel.percentageBar + '%';
    levelName.innerText = userLevel.name;
    remainingTasks.innerText = userLevel.remainingTasks;
    emailInput.setAttribute('value', user.email);
    passwordInput.setAttribute('value', user.senha);
    idHolder.setAttribute('value', user.id);

    const defaultProfilePic = '../view/assets/img/profile.png';
    profilePicture.src = '../view/assets/img/' + user.foto_perfil || defaultProfilePic;
}

async function editUser(event) {
    event.preventDefault();
    let rawFormContent = new FormData(userForm);
    const formData = Object.fromEntries(rawFormContent);

    try {
        const response = await fetch("UsuarioController.php?action=edit", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ formData })
        });

        const responseData = await response.json();

        if (!responseData.ok || response.status === 400) {
            notificate("error", "Error", "There was an error while updating the user");
            return;
        }

        notificate('success', 'Success', 'The user was updated successfully');
        getUserData();
    } catch (error) {
        notificate('error', 'Error', error.message);
    }
}

userForm.addEventListener('submit', editUser);

const btnEmail = document.querySelector('#btnEmail');
const btnPassword = document.querySelector('#btnPassword');
const btnShowPassword = document.querySelector('.eyeIcon');

btnPassword.addEventListener("click", (event) => {
    event.preventDefault();
    passwordInput.removeAttribute('readonly');
});

btnEmail.addEventListener('click', (event) => {
    event.preventDefault();
    emailInput.removeAttribute('readonly');
});

btnShowPassword.addEventListener('click', (event) => {
    event.preventDefault();

    if(passwordInput.getAttribute('type')){
        passwordInput.removeAttribute('type');
    } else {
        passwordInput.setAttribute('type', 'password');
    }
})
