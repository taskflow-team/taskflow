function updateTaskList(tasks) {
    // Limpa a lista de tarefas existente
    $("#taskList").empty();

    // Adiciona cada tarefa à lista de tarefas
    tasks.forEach(function (task) {
        $("#taskList").append(
            "<li class='task' >" +
            "<div>" +
            "<p><strong>" + task.nome_tarefa + "</strong></p>" +
            "<p>" + task.descricao_tarefa + "</p>" +
            "</div>" +
            "<a class='deleteBtn' href='#' data-id='" + task.id_tarefa + "'>Delete</a>" +
            "<div class='difficulty " + task.dificuldade + "'></div>" +
            "</li>"
        );
    });

    // Verifica se a lista de tarefas está vazia e exibe uma mensagem apropriada
    if (tasks.length === 0) {
        $("#taskList").append("<p>No pending tasks</p>");
    }

    // Anexa um evento de clique ao botão de exclusão de tarefa
    $(".deleteBtn").click(deleteTask);
}

async function deleteTask() {
    const taskId = $(this).data("id");

    try {
        const response = await fetch(`TarefaController.php?action=delete&id=${taskId}`, {
            method: "GET",
        });

        if (!response.ok) {
            console.log("A requisição ao servidor falhou");
        } else {
            const responseData = await response.json();
            console.log(responseData);

            // Realiza um fetch da lista de tarefas atualizada após excluir a tarefa
            fetchTaskList();
        }
    } catch (error) {
        console.log(`Ocorreu um erro durante a requisição: ${error}`);
    }
}

// Função para obter a lista de tarefas atualizada do servidor
function fetchTaskList() {
    $.ajax({
        type: "GET",
        url: "/taskflow/app/controller/TarefaController.php?action=list",
        dataType: "json",
        success: function (response) {
            if (response.message === "Success") {
                // Atualiza a lista de tarefas na página
                updateTaskList(response.data);
            } else {
                console.error("Ocorreu um erro ao obter a lista de tarefas.");
            }
        },
        error: function (xhr, status, error) {
            console.error("Erro: " + status + " - " + error);
        }
    });
}

// Chama a função fetchTaskList para carregar a lista de tarefas inicial na carga da página
fetchTaskList();

const showMore = document.querySelector("#showMore");
const formDiv = document.querySelector("#formDiv");
const taskForm = document.querySelector('#frmTarefa');

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

async function createTask(event) {
    event.preventDefault();

    const userID = Number(document.querySelector('#idUsuario').value);

    const rawFormContent = new FormData(taskForm);
    const formData = Object.fromEntries(rawFormContent);
    taskForm.reset();

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

        if (!response.ok) {
            console.log('A requisição ao servidor falhou');
        }

        const responseData = await response.json();
        console.log(responseData);

        // Obtém a lista de tarefas atualizada após criar a tarefa
        fetchTaskList();
    } catch (error) {
        console.log(`Ocorreu um erro durante a requisição: ${error}`);
    }
}

taskForm.addEventListener('submit', createTask);
