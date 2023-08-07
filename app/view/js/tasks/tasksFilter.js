const completedBtn = document.querySelector('#completedTasks');
const incompletedBtn = document.querySelector('#incompletedTasks');
const subFilter = document.querySelector('#subFilter');
const searchBtn = document.querySelector('#searchBtn');

// Função para filtrar as tarefas concluídas e não concluídas
function handleTasksVisibility(element) {
    const tasks = document.querySelectorAll('.task');
    const target = element.target;
    target.classList.toggle('button-active');

    if(target.id === 'incompletedTasks' && target.classList.contains('button-active')){
        completedBtn.classList.remove('button-active');
        filterTasks('incompleted');
    } else if(target.id === 'completedTasks' && target.classList.contains('button-active')){
        incompletedBtn.classList.remove('button-active');
        filterTasks('completed');
    } else {
        filterTasks();
    }
}

function filterTasks(filter){
    let tasks = document.querySelectorAll('.task');

    tasks.forEach(task => {
        switch (filter) {
            case 'incompleted':
                task.style.display = task.classList == 'task checked' ? 'none' : 'block';
                break;
            case 'completed':
                task.style.display = task.classList == 'task checked' ? 'block' : 'none';
                break;
            default:
                task.style.display = 'block';
                break;
        }
    });
}

// Adiciona o evento de clique aos botões de filtro de conclusão de tarefas
completedBtn.addEventListener('click', handleTasksVisibility);
incompletedBtn.addEventListener('click', handleTasksVisibility);

function searchByName(){
    let currentTasks = document.querySelectorAll('.task');
    let nameForSearch = document.querySelector('#taskNameSearch').value;

    if(nameForSearch == ''){
        if(nameForSearch == '' && completedBtn.classList.contains('button-active')){
            filterTasks('completed');
        } else if(nameForSearch == '' && incompletedBtn.classList.contains('button-active')){
            filterTasks('incompleted');
        } else {
            filterTasks();
        }
    } else {
        currentTasks.forEach(task => {
            let taskName = task.firstChild.children[1].children[0].innerText;

            if(taskName.includes(nameForSearch)){
                task.style.display = 'block';
            } else {
                task.style.display = 'none';
            }

        });
    }
}

searchBtn.addEventListener('click', searchByName);

export {
    handleTasksVisibility,
    filterTasks
}
