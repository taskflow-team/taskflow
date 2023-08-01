// Form view
const showMore = document.querySelector("#showMore");
const formDiv = document.querySelector("#formDiv");

function showForm() {
    if (formDiv.style.display === 'none') {
        formDiv.style.display = 'block';
        showMore.innerText = 'Show less';
    } else {
        formDiv.style.display = 'none';
        showMore.innerText = 'Show more';
    }
}

showMore.addEventListener("click", showForm);
