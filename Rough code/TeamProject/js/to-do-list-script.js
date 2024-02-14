const inputBox = document.getElementById("input-box");
const listContainer = document.getElementById("list-container");
const userId = getUserId();

window.addEventListener('load', function() {
    const savedTasks = JSON.parse(localStorage.getItem(`tasks_${userId}`));
    if (savedTasks) {
        savedTasks.forEach(task => {
            addTaskToList(task);
        });
    }
});


function addTaskToList(taskContent) {
    const existingTasks = listContainer.querySelectorAll('li');
    for (let i = 0; i < existingTasks.length; i++) {
        if (existingTasks[i].textContent.trim() === taskContent.trim()) {
            // If the task already exists, do not add it again
            return;
        }
    }
    let li = document.createElement("li");
    li.innerHTML = taskContent;
    listContainer.appendChild(li);
    let span = document.createElement("span");
    span.innerHTML = "\u00d7";
    li.appendChild(span);
}

function addTask() {
    const inputBox = document.getElementById("input-box");
    const taskContent = inputBox.value.trim();
    
    if (taskContent === '') {
        alert("You must write something!");
    } else {
        addTaskToList(taskContent);
        saveData();
    }
    inputBox.value = "";
}

listContainer.addEventListener("click", function(e) {
    if (e.target.tagName === "LI") {
        e.target.classList.toggle("checked");
        saveData();
    } else if (e.target.tagName === "SPAN") {
        e.stopPropagation();
        e.target.parentElement.remove();
        saveData();
    }
}, false);

function getUserId() {
    return sessionStorage.getItem("user_id");
}

function saveData() {
    const tasks = [];
    listContainer.querySelectorAll('li').forEach(li => {
        tasks.push(li.innerText);
    });
    localStorage.setItem(`tasks_${userId}`, JSON.stringify(tasks));
}