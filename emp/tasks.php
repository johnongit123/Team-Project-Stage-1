<?php
require_once '../includes/session-config.php';
$memberID = check_login();
require_once '../includes/dbh.php';
require_once 'new_td_task.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Dashboard</title>
    <!--Icons-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <!--Bootsrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" crossorigin="anonymous">
    <!--CSS-->
    <link rel="stylesheet" href="../css/main_styles.css">
    <link rel="stylesheet" href="../css/sidebar_styles.css">
</head>  
<body>
    <!--Header-->
    <div class="header">
        <div class="header-left">
            <p class="font-weight-bold">TASKS</p>
        </div>
        <div class="header-right">
            <a href=# id="invite-link">Invite to System</a>

            <div id="invite-popup" class="popup">
                <div class="popup-content">
                    <span class="close" onclick="closePopup()">&times;</span>
                    <p>Here's your invite code link:</p>
                    <input type="text" id="invite-link-input" class="popup-input" readonly>
                    <button id="copyButton" class="popup-button" onclick="copyLink()">Copy</button>
                </div>
            </div>
        </div>   
    </div>   


    <!--Sidebar/Navigation-->
    <div class="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-logo">
                <i class="fa-solid fa-screwdriver-wrench"></i>
                <span>MakeItAll</span>
            </div>
            <i class="fa-solid fa-bars" id="btn"></i>
        </div>
        <ul class="sidebar-list">
            <li class="sidebar-list-item">
                <a href="tasks.php">
                    <span class="material-symbols-outlined">task</span>
                    <span class="text">Tasks</span>
                    <span class="tooltip">Tasks</span>
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="project.php">
                    <span class="material-symbols-outlined">monitoring</span>
                    <span class="text">Projects</span>
                    <span class="tooltip">Projects</span>
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="threads.php">
                <span class="material-symbols-outlined">mode_comment</span>
                    <span class="text">Threads</span>
                    <span class="tooltip">Threads</span>
                </a>
            </li>
            <li class="sidebar-list-item logoutMenu">
                <a href="../includes/logout.php" id="logoutLink">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text">Logout</span>
                    <span class="tooltip">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!--window shadow effect-->
    <div class="shadow-effect" id="shadow-effect"></div>

    <!--Main Content-->
    <main class="main-container">
        <div class="main-title">
            <p class="font-weight-bold">EMPLOYEE VIEW</p>
        </div>
        <div class="charts single-view">
            <div class="charts-card">
                <!--Card header portion-->
                <div class="chart-header">
                    <p class="chart-title">TASK LIST</p>
                </div>
                <div class="divider"></div>
                <div class="chart-list">
                    <!--Table contents-->   
                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="5%">
                            <col width="30%">
                            <col width="15%">
                            <col width="10%">
                            <col width="10%">
                            <col width="15%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>ID</th>
                                <th>Task</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Manager</th>
                                <th>Project</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="project-tdbody">
                            <!--All the task tables-->    
                            <?php
                            $sql = "SELECT task.*
                            FROM task
                            INNER JOIN assigns ON task.task_id = assigns.task_id
                            INNER JOIN employee ON assigns.emp_id = employee.emp_id
                            WHERE employee.emp_id = ?
                            ORDER BY task.end_date";                            
                            if ($stmt = $con->prepare($sql)) {
                                $stmt->bind_param("i", $memberID);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $tasks = [];

                                if ($result->num_rows > 0) {
                                    // Fetch each row of the result as an associative array and store it in $tasks
                                    while($row = $result->fetch_assoc()) {
                                        $tasks[] = $row;
                                    }
                                } else {
                                    echo "<tr><td colspan='7'><p class='form-error' style='margin-top: 10px;'>No tasks have been assigned</p></td></tr>";
                                }
                                
                                foreach ($tasks as $index => $task) {
                                    echo "<tr>";
                                    echo "<td>" . ($index + 1) . "</td>";
                                    echo "<td>" . $task['task_id'] . "</td>";
                                    echo "<td>" . $task['task_name'] . "</td>";
                                    echo "<td>" . $task['end_date'] . "</td>";
                                    echo "<td>" . $task['status'] . "</td>";               
                                    echo "<td>" . $task['priority'] . "</td>";
                                    echo "<td>" . ($task['manager_name'] ? $task['manager_name'] : '-') . "</td>";
                                    echo "<td>" . $task['project_id'] . "</td>";
                                    echo '<td> <button id="task-completed-btn" class="task-completed-button" data-task-id="' . $task['task_id'] . '">Task Completed</button> </td>';
                                    echo "</tr>";
                                }
                            } else {
                                // Handle errors if needed
                                echo "Error: " . $con->error;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="charts-card">
                <!--Card header portion-->
                <div class="chart-header">
                    <p class="chart-title">TO DO LIST</p>
                    <div class="add-task-button">
                        <button id="add-task-button" class="add-task-button">Add Task</button>
                    </div>
                </div>
                <div class="divider"></div>
                <?php
                    success_tdtask();
                    check_tdtask_errors();
                ?>
                <div id="task-chart" class="task-chart">
                <?php
                $sql2 = "SELECT * FROM tdl WHERE emp_id = ? ORDER BY due_date DESC";
                if ($stmt2 = $con->prepare($sql2)) {
                    $stmt2->bind_param("i", $memberID);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();

                    if ($result2->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result2->fetch_assoc()) {
                            $taskStatusClass = ($row["status"] == 'Completed') ? 'completed' : ''; // Check if status is Completed

                            echo "<div class='task-item $taskStatusClass' data-task-id='{$row['tdl_id']}'>";
                            echo "<button class='complete-task-button $taskStatusClass' data-task-id='{$row['tdl_id']}' name='complete_task'></button>";                            
                            echo "<p class='task-text $taskStatusClass'>" . $row["tdl_name"] . "</p>";
                            echo "<span class='deadline'>Due on: " . $row["due_date"] . "</span>";
                            echo "<button type='submit' class='delete-task-button' data-task-id='{$row['tdl_id']}' name='delete_task'>Delete</button>";
                            echo "</div>";
                            echo "<div class='divider'></div>";
                        }
                    }
                    $stmt2->close();
                } else {
                    echo "<p>Error fetching tasks: " . $con->error . "</p>";
                }
                ?>
                </div>
                <div id="task-form-container" class="project-form-container">
                    <div class="project-form-content" >
                        <h2>Add Task</h2>
                        <div class="divider"></div>
                        <form id="task-form" action="new_td_task.php" method="post">
                            <div class="row">
                                <div class="col-md-6"> 
                                    <input type="text" name="task_name" class="task-input" placeholder="Task Name" required>
                                </div>
                                <div class="col-md-6"> 
                                    <input type="date" name="due_date" class="due-date-input" placeholder="Due Date" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="task-submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>     
    </main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="../js/sidebar_scripts.js"></script>
<script>
    

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('invite-link').addEventListener('click', openPopup);

        //add todolist button scripts
        const addTdTaskButton = document.getElementById("add-task-button");
        const tdTaskFormContainer = document.getElementById("task-form-container");

        addTdTaskButton.addEventListener("click", () => {
            tdTaskFormContainer.style.display = "flex";
            shadow_effect.style.display = "flex"; 
        });

        tdTaskFormContainer.addEventListener("click", (e) => {
            if (e.target === tdTaskFormContainer) {
                tdTaskFormContainer.style.display = "none";
                shadow_effect.style.display = "none";
            }
        });
        
        attachCompleteButtonListeners();
        attachDeleteButtonListeners();
    });

    const shadow_effect = document.getElementById("shadow-effect");
    
    //Invitation scripts
    // Function to open the popup

    function openPopup() {
        var popup = document.getElementById('invite-popup');
        var inviteCodeInput = document.getElementById('invite-link-input');
        var inviteCode = generateInviteCode();
        inviteCodeInput.value = "http://35.246.76.223/team-projects-part-2-team-20/register.php?invitecode=" + inviteCode; //MATCH TO VIRTUALMACHINE
        popup.style.display = 'block';
        shadow_effect.style.display = "flex";
    }

        
    function generateInviteCode(length = 6){
        const characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let code = '';
        for (let i = 0; i < length; i++) {
            code += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return code;
    }

    // Function to close the popup
    function closePopup(){
        var popup = document.getElementById('invite-popup');
        popup.style.display = 'none';
        shadow_effect.style.display = "none";
    }


    function copyLink() {
        var linkInput = document.getElementById("invite-link-input");
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();
        
        // Changes button text to "Copied"
        var copyButton = document.getElementById("copyButton");
        copyButton.textContent = "Copied!";
        copyButton.classList.add("copied");

        // reset button after a delay of 4 seconds
        setTimeout(function() {
            copyButton.textContent = "Copy";
            copyButton.classList.remove("copied");
        }, 4000);
    }

     // Select the button with the id 'task-completed-btn'
    var taskCompletedButton = document.getElementById('task-completed-btn');

    // Attach a click event listener to the button
    taskCompletedButton.addEventListener('click', function() {
        // Retrieve the task ID from the 'data-task-id' attribute
        var taskId = this.getAttribute('data-task-id');
        
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        
        // Define the PHP file URL and parameters
        var url = 'process_task.php';
        var params = 'task_id=' + taskId;
    

        // Configure the request
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        // Define the callback function
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                // Handle the response from the PHP file
                console.log(xhr.responseText);
                location.reload();
                alert("Well Done For Completing Task");
                // You can perform additional actions based on the response
            }
        };

        // Send the request
        xhr.send(params);
    });

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
                var tdTaskId = this.getAttribute('data-task-id'); // Ensure you have 'data-task-id' attribute on the button
                var newStatus = this.classList.contains('completed') ? 'Completed' : 'In Progress';
                console.log(`Updating task ${tdTaskId} to ${newStatus}`);
                
                fetch('update_tdtask_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `task_id=${tdTaskId}&status=${newStatus}`
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



    function attachDeleteButtonListeners() {
        const deleteButtons = document.querySelectorAll('.delete-task-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tdTaskId = this.getAttribute('data-task-id');
                if (confirm('Are you sure you want to delete this task?')) {
                    deleteTask(tdTaskId);
                }
            });
        });
    }

    function deleteTask(tdTaskId) {
        fetch('delete_td_task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `tdl_id=${tdTaskId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                console.log('Task deleted successfully.');
                // Find the task item element and remove it from the DOM
                const taskItem = document.querySelector(`.task-item[data-task-id="${tdTaskId}"]`);
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

</script>
</body>
</html>
