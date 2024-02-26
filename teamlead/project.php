<?php
require_once '../includes/session-config.php';
$memberID = check_login();
require_once '../includes/dbh.php';
require_once 'edit_project.php';
require_once 'delete_project.php';
require_once 'add_tasks.php';
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
            <p class="font-weight-bold">PROJECTS</p>
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
            <p class="font-weight-bold">PROJECT LEADER VIEW</p>
        </div>
        
        <div class="charts single-view big-chart">
            <div class="charts-card">
                <!--Card header portion-->
                <div class="chart-header">
                    <p class="chart-title">PROJECTS LIST</p>
                    <div class="button-group">
                        <div class="create-task-button">
                            <button id="create-task-button">Add Task</button>
                        </div>
                        <div class="edit-project-button">
                            <button id="edit-project-button">Edit Project</button>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <?php 
                success_edit();
                success_task();
                check_edit_errors();
                check_task_errors();
                ?>
                <div class="chart-list">
                    <!--Table contents-->   
                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="5%">
                            <col width="30%">
                            <col width="15%">
                            <col width="15%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>ID</th>
                                <th>Project</th>
                                <th>Date Started</th>
                                <th>Due Date</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="project-tdbody">
                            <!--All the project tables-->    
                            <?php
                            $sql = "SELECT project.*
                            FROM project
                            INNER JOIN assigns ON project.project_id = assigns.project_id
                            INNER JOIN employee ON assigns.emp_id = employee.emp_id
                            WHERE employee.emp_id = ?
                            ORDER BY project.end_date";                            
                            if ($stmt = $con->prepare($sql)) {
                                $stmt->bind_param("i", $memberID);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $projects = [];

                                if ($result->num_rows > 0) {
                                    // Fetch each row of the result as an associative array and store it in $projects
                                    while($row = $result->fetch_assoc()) {
                                        $projects[] = $row;
                                    }
                                } else {
                                    echo "<tr><td colspan='8'><p class='form-error' style='margin-top: 10px;'>No Projects have been assigned</p></td></tr>";
                                }
                                
                                foreach ($projects as $index => $project) {
                                    echo "<tr>";
                                    echo "<td>" . ($index + 1) . "</td>";
                                    echo "<td>" . $project['project_id'] . "</td>";
                                    echo "<td><p class='proj_name'>" . $project['project_name'] . "</p>
                                    <p class='sub-text'>" . (str_word_count($project['description']) > 10 ? implode(' ', array_slice(str_word_count($project['description'], 1), 0, 10)) . "..." : $project['description']) . "</p>
                                    </td>";
                                    echo "<td>" . $project['start_date'] . "</td>";
                                    echo "<td>" . $project['end_date'] . "</td>";
                                    // Calculate duration
                                    $dueDate = strtotime($project['end_date']);
                                    $currentDate = time();
                                    $duration = $dueDate - $currentDate;
                                    $daysLeft = floor($duration / (60 * 60 * 24));

                                    // Display duration with appropriate color
                                    if ($daysLeft == 0) {
                                        echo "<td style='color: red;'>0</td>";
                                    } else {
                                        echo "<td>$daysLeft day(s)</td>";
                                    }
                                    echo "<td>" . $project['status'] . "</td>";
                                    
                                    echo "<td>
                                        <div class='dropdown'>
                                            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton{$index}' data-bs-toggle='dropdown' aria-expanded='false'>
                                                Actions
                                            </button>
                                            <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton{$index}'>
                                                <li><a class='dropdown-item delete-action' href='delete_project.php?id={$project['project_id']}'>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>";
                                    
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
                <!--Edit form-->
                <div id="edit-form-container" class="project-form-container">
                    <div class="project-form-content">
                        <h2>Edit Project</h2>
                        <div class="divider"></div>
                        <form id="edit-project-form" action="edit_project.php" method="post">
                            <div class="row align-items-start">
                                <div class="col-md-16">
                                    <label for="select-project">Choose Project:</label>
                                    <select id="select-project" name="project_info" required>
                                        <option value=""></option>
                                        <?php
                                            foreach ($projects as $index => $project) {
                                                echo "<option value='{$project['project_id']}' data-project-id='{$project['project_id']}' data-project-name='{$project['project_name']}' data-start-date='{$project['start_date']}' data-end-date='{$project['end_date']}' data-description='" . htmlspecialchars($project['description']) . "'>#{$project['project_id']} - {$project['project_name']}</option>";
                                            }
                                        ?>
                                    </select>
                                    <input type="hidden" id="edit_project_id" name="project_id">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="edit_project_name">Project Name:</label>
                                        <input type="text" id="edit_project_name" name="project_name" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="edit_status">Status:</label>
                                        <select name="status" id="edit_status" class="form-control form-control-sm" required>
                                            <?php
                                                $stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
                                                foreach ($stat as $value) {
                                                    echo "<option value=\"$value\">$value</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="edit_start_date">Start Date:</label>
                                        <input type="date" id="edit_start_date" name="start_date" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="edit_end_date">End Date:</label>
                                        <input type="date" id="edit_end_date" name="end_date" class="form-control form-control-sm" required>
                                    </div>           
                                </div>                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="edit_description">Description:</label>
                                        <textarea name="description" id="edit_description" cols="30" rows="10" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="edit-button btn">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Task Creation -->                                  
                <div id="task-form-container" class="project-form-container">
                    <div class="project-form-content">
                        <h2>Add Tasks</h2>
                        <div class="divider"></div>
                        <form id="task-form" action="add_tasks.php" method="post">
                            <div class="row align-items-start">
                                <div class="col-md-12"> 
                                    <label for="choose-project">Choose Project:</label>
                                    <select id="choose-project" name="project_info" required>
                                        <option value=""></option>
                                        <?php
                                            foreach ($projects as $index => $project) {
                                                echo "<option value='{$project['project_id']}' data-project-id='{$project['project_id']}'>#{$project['project_id']} - {$project['project_name']}</option>";
                                            }
                                        ?>
                                        <!-- Add more options here -->
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12"> 
                                    <div class="form-group">
                                        <label for="task_name">Task Name:</label>
                                        <input type="text" id="task_name" name="task_name" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="task_status">Status:</label>
                                        <select name="status" id="task_status" class="form-control form-control-sm" required>
                                            <?php
                                                $stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
                                                foreach ($stat as $value) {
                                                    echo "<option value=\"$value\">$value</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="priority">Priority:</label>
                                        <select name="priority" id="priority" class="form-control form-control-sm" required>
                                            <?php
                                                $prio = array("High","Medium","Low");
                                                foreach ($prio as $p_value) {
                                                    echo "<option value=\"$p_value\">$p_value</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="task_deadline">Choose Deadline:</label>
                                        <input type="date" id="task_deadline" name="end_date" class="form-control form-control-sm" required>
                                    </div>           
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="task_emp_select" class="control-label">Team Members:</label>
                                        <select name="emp_select" id="task_emp_select" onchange="updateSelectedEmployee()">
                                            <option value=""></option>
                                            <?php
                                            $emp_sql = "SELECT DISTINCT employee.emp_id, employee.first_name, employee.last_name, COUNT(assigns.task_id) AS num_assignments 
                                            FROM employee 
                                            LEFT JOIN assigns ON employee.emp_id = assigns.emp_id 
                                            WHERE job_role = 'employee' AND is_reg = 'Y' 
                                            GROUP BY employee.emp_id, employee.first_name, employee.last_name";

                                            $emp_result = $con->query($emp_sql);


                                            if ($emp_result->num_rows > 0) {
                                                // Fetch each row of the result as an associative array and store it in $projects
                                                while($emp_row = $emp_result->fetch_assoc()) {
                                                    $e_fullname = $emp_row['first_name'].' '. $emp_row['last_name'];
                                                    $emp_id = $emp_row['emp_id'];
                                                    $e_disabled = $emp_row['num_assignments'] >= 1 ? "disabled" : ""; // Check if employee has at least 1 assignments
                                                    echo "<option value=\"" . htmlspecialchars($e_fullname) . "\" data-emp-id=\"$emp_id\" $e_disabled>" . htmlspecialchars($e_fullname) . "</option>";
                                                }
                                            } else {
                                                echo "<option class='form-error'>No Results</p>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="task_project_id" name="project_id"> 
                            <input type="hidden" id="task_manager_id" name="manager_id" value="<?php echo $memberID; ?>">
                            <input type="hidden" id="selectedEmployee" name="employee_id">                        
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="create-button btn">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--Description page-->
                <div class="description-container" id="description-container">
                    <div class="description-content">
                        <p id="description-text"></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="../js/sidebar_scripts.js"></script>
<script>

    $(document).ready(function () {
        $('#select-project').change(function () {
            var projectOption = $(this).find('option:selected');
            
            var projectId = projectOption.attr('data-project-id');
            var projectName = projectOption.attr('data-project-name');
            var startDate = projectOption.attr('data-start-date');
            var endDate = projectOption.attr('data-end-date');
            var description = projectOption.attr('data-description');

            $('#edit_project_id').val(projectId);
            $('#edit_project_name').val(projectName);
            $('#edit_start_date').val(startDate);
            $('#edit_end_date').val(endDate);
            $('#edit_description').val(description);
        });
    });

    $(document).ready(function () {
        $('#choose-project').change(function () {
            var projectSelected = $(this).find('option:selected');
            
            var projectID = projectSelected.attr('data-project-id');

            $('#task_project_id').val(projectID);
        });
    });
    
    document.addEventListener("DOMContentLoaded", function () {
        //invite to register popup script
        document.getElementById('invite-link').addEventListener('click', openPopup);

        //description button scripts
        const descriptionData = <?php echo json_encode($projects); ?>;

        const rows = document.querySelectorAll("tbody#project-tdbody tr");
        rows.forEach(row => {
            const projectNameElement = row.querySelector('.proj_name');
            
            projectNameElement.addEventListener("click", () => {
                event.stopPropagation(); // ensuring the parent elements arent affected by click

                const projectName = projectNameElement.textContent;
                const project = descriptionData.find(proj => proj.project_name === projectName);
                const description = project ? project.description : '';

                const descriptionText = document.getElementById("description-text");
                descriptionText.innerHTML = `<h2>${projectName}</h2><div class="divider"></div><p>${description}</p>`;
                
                openDescription();
            });
        });
        
       //edit button scripts
        const editProjectButton = document.getElementById("edit-project-button");
        const editFormContainer = document.getElementById("edit-form-container");

        editProjectButton.addEventListener("click", () => {
            editFormContainer.style.display = "flex";
            shadow_effect.style.display = "flex"; 
        });

        editFormContainer.addEventListener("click", (e) => {
            if (e.target === editFormContainer) {
                editFormContainer.style.display = "none";
                shadow_effect.style.display = "none";
            }
        });

        const createTaskButton = document.getElementById("create-task-button");
        const taskFormContainer = document.getElementById("task-form-container");

        createTaskButton.addEventListener("click", () => {
            taskFormContainer.style.display = "flex";
            shadow_effect.style.display = "flex"; 
        });

        taskFormContainer.addEventListener("click", (e) => {
            if (e.target === taskFormContainer) {
                taskFormContainer.style.display = "none";
                shadow_effect.style.display = "none";
            }
        });
    });

    function updateSelectedEmployee(){
        var select = document.getElementById("task_emp_select");
        var selectedEmployee = select.options[select.selectedIndex];
        var empId = selectedEmployee.dataset.empId;

        document.getElementById("selectedEmployee").value = empId;
    }
    
   
    
    const shadow_effect = document.getElementById("shadow-effect");
    const descriptionContainer = document.getElementById("description-container");
    
    
    descriptionContainer.addEventListener("click", (e) => {
        // If the click is not on the description content, close the description container
        if (e.target === descriptionContainer) {
                closeDescription();
            }
    });

    function openDescription() {
        descriptionContainer.style.display = "flex";
        shadow_effect.style.display = "flex";
    }

    function closeDescription() {
        descriptionContainer.style.display = "none";
        shadow_effect.style.display = "none";
    }

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

</script>
</body>
</html>
