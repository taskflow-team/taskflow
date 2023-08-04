import notificate from './notification.js';

const nameHolder = document.querySelector('#user-name');
const loginHolder = document.querySelector('#user-login');
const nivelHolder = document.querySelector('#user-nivel');
const finishedTasksHolder = document.querySelector('#finished-tasks');
const emailInput = document.querySelector('#user-email');
const passwordInput = document.querySelector('#user-password');
const userForm = document.querySelector('#frmEditUsuario');
const idHolder = document.querySelector('#userId');

// Async requests
function getUserData(){
    $.ajax({
        type: "GET",
        url: "UsuarioController.php?action=getUserData",
        success: (response) => {
            if(!response.ok || response.status == 400){
                notificate("error", "Error", "There was an error while getting user data");
                return;
            }

            updateUserData(response.user);
        },
        error: (xhr, status, error) => {
            notificate('error', 'Error', error);
        }
    });
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
}

function editUser(event){
    event.preventDefault();
    let rawFormContent = new FormData(userForm);
    const formData = Object.fromEntries(rawFormContent);

    $.ajax({
        type: "POST",
        url: "UsuarioController.php?action=edit",
        contentType: "application/json", // Set the content type to JSON
        dataType: "json", // Expect JSON response
        data: JSON.stringify({ formData }), // Send the data as JSON string
        success: (response) => {
            if (!response.ok || response.status == 400) {
                notificate("error", "Error", "There was an error while updating the user");
                return;
            }

            notificate('success', 'Success', 'The user was updated successfully');

            getUserData();
        },
        error: (xhr, status, error) => {
            notificate('error', 'Error', error);
        }
    });
}

userForm.addEventListener('submit', editUser);

// View functions
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
