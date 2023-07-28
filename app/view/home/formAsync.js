const showMore = document.querySelector("#showMore");
const formDiv = document.querySelector("#formDiv");
const taskForm = document.querySelector('#frmTarefa');

function showForm(){
    if (formDiv.style.display === 'none') {
        formDiv.style.display = 'block';
        showMore.innerText = 'Show less';
    } else {
        formDiv.style.display = 'none';
        showMore.innerText = 'Show more';
    }
}

showMore.addEventListener("click", showForm);

async function createTask(event){
    event.preventDefault();

    // Move the userID assignment here
    const userID = document.querySelector('#idUsuario').value;

    // Get form content, turns it into an object and clears the form
    const rawFormContent = new FormData(taskForm);
    const formData = Object.fromEntries(rawFormContent);
    taskForm.reset();

    // Async request to the tarefaController
    try {
        const reqConfigs = {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                formData: formData,
                userID: userID
            })
        };

        const response = await fetch('TarefaController.php?action=save', reqConfigs);

        // Validates if req has succeeded
        if(!response.ok){
            console.log('The request to the server has failed');
        }

        // Prints response data to the console
        const responseData = await response.json();
        console.log(responseData);

    } catch (error) {
        console.log(`An error occurred during the request: ${error}`);
    }
}

taskForm.addEventListener('submit', createTask);
