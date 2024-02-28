document.addEventListener('DOMContentLoaded', function() {

    fetchTasks();
    attachCompleteButtonListeners();
    // Select all task completion buttons
    var completeButtons = document.querySelectorAll('.complete-task-button');
    

    const taskForm = document.getElementById('task-form');
    const addTaskButton = document.getElementById("add-task-button");
    const taskFormContainer = document.getElementById("task-form-container");

    addTaskButton.addEventListener("click", function() {
        
        taskFormContainer.style.display = "flex"; // Show the form pop-up
    });

    // Hide the form when clicking outside of it
    window.addEventListener("click", function(event) {
        if (event.target === taskFormContainer) {
            taskFormContainer.style.display = "none";
        }
    });

    taskForm.addEventListener("submit", function(event) {
        event.preventDefault();
        var formData = new FormData(taskForm);
        fetch('add_task.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
               // alert('Task added: ' + data.message);
                taskFormContainer.style.display = "none"; // Hide the form
                taskForm.reset(); // Reset the form for the next input
                fetchTasks(); // Refresh the tasks
                showMyTasks(); // Switch to the 'My Tasks' view
            } else {
                alert('Error adding task: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    
//fetch tasks for to do list 
    function fetchTasks() {
        fetch('get_tasks.php')
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                const tasks = data.tasks;
                const taskList = document.getElementById('task-chart');
                taskList.innerHTML = ''; // Clear existing tasks
    
                tasks.forEach(task => {
                    const completedClass = task.status === 'completed' ? 'completed' : '';
    
                    taskList.innerHTML += `
                    <div class="task-item ${completedClass}" data-task-id="${task.task_id}">
                        <button class="complete-task-button ${completedClass}" data-task-id="${task.task_id}"></button>
                        <p class="task-text ${completedClass}" style="flex-grow: 1;">${task.task_name}</p>
                        <span class="due-date">Due on: ${task.due_date}</span>
                        <button class="delete-task-button" data-task-id="${task.task_id}">Delete</button>
                    </div>
                    <div class="divider"></div>
                `;
                
                });
    
                attachCompleteButtonListeners();
                attachDeleteButtonListeners(); // Make sure to implement this function
            } else {
                console.error('Failed to fetch tasks:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    
    //complete tasks button 
    var completeButtons = document.querySelectorAll('.complete-task-button');
    function attachCompleteButtonListeners() {
        var completeButtons = document.querySelectorAll('.complete-task-button');
        
        completeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Toggle the 'completed' class on the button to change its appearance
                this.classList.toggle('completed');
    
                // If the button is inside a task item, you can toggle the task text class as well
                var taskText = this.nextElementSibling;
                if (taskText && taskText.classList.contains('task-text')) {
                    taskText.classList.toggle('completed');
                }
    
                // Optionally, send an update to the server to change the task status in the database
                // You would need the task ID and the new status ("completed" or "not completed")
                var taskId = this.getAttribute('data-task-id'); // Ensure you have 'data-task-id' attribute on the button
                var newStatus = this.classList.contains('completed') ? 'completed' : 'in progress';
                console.log(`Updating task ${taskId} to ${newStatus}`);
                
                fetch('update_task_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `task_id=${taskId}&status=${newStatus}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        console.log('Task status updated successfully.');
                    } else {
                        console.error('Failed to update task status:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
    
            });
        });
    }
    


});
function attachDeleteButtonListeners() {
    const deleteButtons = document.querySelectorAll('.delete-task-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            if (confirm('Are you sure you want to delete this task?')) {
                deleteTask(taskId);
            }
        });
    });
}
function deleteTask(taskId) {
    fetch('delete_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `task_id=${taskId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log('Task deleted successfully.');
            // Find the task item element and remove it from the DOM
            const taskItem = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
            if (taskItem) {
                taskItem.remove();
            }
        } else {
            console.error('Failed to delete task:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}