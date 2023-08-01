const btnEmail = document.querySelector('#btnEmail');
const emailInput = document.querySelector('#txtEmail');
const btnPassword = document.querySelector('#btnPassword');
const passwordInput = document.querySelector('#txtPassword');
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
