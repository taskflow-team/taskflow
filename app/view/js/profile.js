import notificate from './notification.js';

const nameHolder = document.querySelector('#user-name');
const loginHolder = document.querySelector('#user-login');
const nivelHolder = document.querySelector('#user-nivel');
const finishedTasksHolder = document.querySelector('#finished-tasks');
const emailInput = document.querySelector('#user-email');
const passwordInput = document.querySelector('#user-password');

// Async requests
function getUserData(){
    $.ajax({
        type: "GET",
        url: "UsuarioController.php?action=getUserData",
        success: (response) => {
            console.log(response);
        },
        error: (xhr, status, error) => {
            notificate('error', 'Error', error);
        }
    });
}

getUserData();

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
