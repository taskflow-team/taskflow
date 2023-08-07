const completedBtn = document.querySelector('#completedTasks');
const incompletedBtn = document.querySelector('#incompletedTasks');
const subFilter = document.querySelector('#subFilter');

// Função para filtrar as tarefas concluídas e não concluídas
function handleTasksVisibility(element) {
    const tasks = document.querySelectorAll('.task');
    const target = element.target;
    target.classList.toggle('button-active');

    if(target.id === 'incompletedTasks' && target.classList.contains('button-active')){
        completedBtn.classList.remove('button-active');
        filterTasks('incompleted', tasks);
    } else if(target.id === 'completedTasks' && target.classList.contains('button-active')){
        incompletedBtn.classList.remove('button-active');
        filterTasks('completed', tasks);
    } else {
        filterTasks('', tasks);
    }
}

function filterTasks(filter){
    const tasks = document.querySelectorAll('.task');
    let activeTasks = [];

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

export {
    handleTasksVisibility,
    filterTasks
}
