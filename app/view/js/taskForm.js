const showMoreBtn = document.querySelector('.showMoreBtn');
const arrowIcon = document.querySelector('.showMoreBtn i');
const formDiv = document.querySelector('#formDiv');

showMoreBtn.addEventListener('click', toggleShowForm);

function toggleShowForm() {
    formDiv.style.display = formDiv.style.display === 'none' ? 'block' : 'none';
    showMoreBtn.classList.toggle('rotated');
}
