import notificate from './notification.js';

const nameHolder = document.querySelector('#user-name');
const loginHolder = document.querySelector('#user-login');
const nivelHolder = document.querySelector('#user-nivel');
const finishedTasksHolder = document.querySelector('#finished-tasks');
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
    nameHolder.innerText = user.nome;
    loginHolder.innerText = user.login;
    nivelHolder.innerText = user.nivel;
    finishedTasksHolder.innerText = user.tarefas_concluidas;
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
