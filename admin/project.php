<?php
require_once '../includes/session-config.php';
$memberID = check_login();
require_once '../includes/dbh.php';
require_once 'new_project.php';
require_once 'edit_project.php';
require_once 'delete_project.php';
require_once 'assign_employee_project.php';
require_once 'remove_employee_from_project.php';
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
            <p class="font-weight-bold">ADMIN VIEW</p>
        </div>
        
        <div class="charts single-view">
            <div class="charts-card">
                <!--Card header portion-->
                <div class="chart-header">
                    <p class="chart-title">PROJECTS LIST</p>
                    <div class="button-group">
                        <div class="create-project-button">
                            <button id="create-project-button">Create Project</button>
                        </div>
                        <div class="edit-project-button">
                            <button id="edit-project-button">Edit Project</button>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <?php
                    success_project();
                    success_edit();
                    success_delete();
                    check_project_errors();
                    check_edit_errors();    
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
				<th>Progress</th>
                            </tr>
                        </thead>
                        <tbody id="project-tdbody">
                            <!--All the project tables-->
                            <?php
                            $sql = "SELECT * FROM project ORDER BY end_date";
                            $result = $con->query($sql);

                            $projects = [];

                            if ($result->num_rows > 0) {
                                // Fetch each row of the result as an associative array and store it in $projects
                                while($row = $result->fetch_assoc()) {
                                    $projects[] = $row;
                                }
                            } else {
                                echo "<p class='form-error'>0 results</p>";
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
				echo "<td> <progress class = 'progressBar' max='100' value='{$progressValue}' data-project-id='{$project['project_id']}'></progress> </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="project-form-container" class="project-form-container">
                    <!--Create project-->
                    <div class="project-form-content">
                        <h2>Create Project</h2>
                        <div class="divider"></div>
                        <form id="project-form" action="new_project.php" method="post">
                            <div class="row">
			                    <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="create_project_name">Project Name:</label>
                                        <input type="text"  id="create_project_name" name="project_name" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="create_status">Status:</label>
                                        <select name="status" id="create_status">
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
                                        <label for="create_start_date">Start Date:</label>
                                        <input type="date" id="create_start_date" name="start_date" class="form-control form-control-sm" required>
                                    </div>
                                </div>

                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="create_end_date">End Date:</label>
                                        <input type="date" id="create_end_date" name="end_date" class="form-control form-control-sm" name="start_time" required>
                                    </div>           
                                </div>                
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="selectedManager" name="selected_manager">
                                        <label for="create_manager_select" class="control-label">Project Manager:</label>
                                        <select name="manager_select" id="create_manager_select" onchange="updateSelectedManager()">
                                            <option value=""></option>
                                            <?php
                                            $tl_sql = "SELECT DISTINCT employee.first_name, employee.last_name, COUNT(assigns.project_id) AS num_assignments 
                                            FROM employee 
                                            LEFT JOIN assigns ON employee.emp_id = assigns.emp_id 
                                            WHERE (job_role = 'team_leader' OR job_role = 'admin') AND is_reg = 'Y' 
                                            GROUP BY employee.first_name, employee.last_name";
                                
                                            $tl_result = $con->query($tl_sql);

                                            $teamlead_lists = [];

                                            if ($tl_result->num_rows > 0) {
                                                // Fetch each row of the result as an associative array and store it in $projects
                                                while($tl_row = $tl_result->fetch_assoc()) {
                                                    $tl_fullname = $tl_row['first_name'].' '. $tl_row['last_name'];
                                                    $tl_disabled = $tl_row['num_assignments'] > 0 ? "disabled" : ""; // Check if manager has any assignments
                                                    echo "<option value=\"" . htmlspecialchars($tl_fullname) . "\" $tl_disabled>" . htmlspecialchars($tl_fullname) . "</option>";
                                                }
                                            } else {
                                                echo "<option class='form-error'>No Results</p></option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <input type="hidden" id="selectedEmployee" name="selected_employees">
                                        <label for="create_emp_select" class="control-label">Team Members:</label>
                                        <select name="emp_select" id="create_emp_select" onchange="updateSelectedEmployee()">
                                            <option value=""></option>
                                            <?php
                                            $emp_sql = "SELECT DISTINCT employee.first_name, employee.last_name, COUNT(assigns.project_id) AS num_assignments
                                            FROM employee 
                                            LEFT JOIN assigns ON employee.emp_id = assigns.emp_id 
                                            WHERE job_role = 'employee' AND is_reg = 'Y' 
                                            GROUP BY employee.first_name, employee.last_name";

                                            $emp_result = $con->query($emp_sql);

                                            $emp_lists = [];

                                            if ($emp_result->num_rows > 0) {
                                                // Fetch each row of the result as an associative array and store it in $projects
                                                while($emp_row = $emp_result->fetch_assoc()) {
                                                    $e_fullname = $emp_row['first_name'].' '. $emp_row['last_name'];
                                                    $e_disabled = $emp_row['num_assignments'] > 0 ? "disabled" : ""; // Check if employee has any existing assignments
                                                    echo "<option value=\"" . htmlspecialchars($e_fullname) . "\" $e_disabled>" . htmlspecialchars($e_fullname) . "</option>";
                                                }
                                            } else {
                                                echo "<option class='form-error'>No Results</p>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-16">
                                    <div class="form-group">
                                        <label for="create_description" class="control-label">Description:</label>
                                        <textarea name="description" id="create_description" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="create-button btn">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
                <!--Description page-->
                <div class="description-container" id="description-container">
                    <div class="description-content">
                        <p id="description-text"></p>
                    </div>
                </div>
            </div>
            

            <!-- This takes a look at all employees and team leaders in the database and checks to see what projects they're assigned to or if they are assigned at all-->
            <div class="charts-card">
                <div class="chart-header">
                    <p class="chart-title">MEMBERS LIST</p>
                    <div class="member-add-button">
                        <button id="member-add-button">Add to Project</button>
                    </div>
                </div>    
                <div class="divider"></div>
                <?php
                    success_assign();
                    success_remove();
                    check_assign_errors();
                    check_remove_errors();
                ?>
                <div class="chart-list">
                    <!--Table contents-->   
                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="5%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">                       
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Role</th>
                                <th>Assignment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employee-tdbody">
                            <!--All the project tables-->
                            <?php
                            $staff_query = "SELECT employee.emp_id, employee.first_name, employee.last_name, employee.job_role, GROUP_CONCAT(DISTINCT assigns.project_id) 
                            AS assigned_projects FROM employee LEFT JOIN assigns ON employee.emp_id = assigns.emp_id WHERE employee.is_reg ='Y' GROUP BY employee.emp_id";
                  
                            $staff_result = $con->query($staff_query);

                            $stafflist = [];

                            if ($staff_result->num_rows > 0) {
                                // Fetch each row of the result as an associative array and store it in $projects
                                while($staff_row = $staff_result->fetch_assoc()) {
                                    $stafflist[] = $staff_row;
                                }
                            } else {
                                echo "<p class='form-error'>0 results</p>";
                            }
                            foreach ($stafflist as $s_index => $staff) {
                                $staff_full_name = $staff['first_name'] . ' ' . $staff['last_name'];
                                echo "<tr>";
                                echo "<td>" . ($s_index + 1) . "</td>";
                                echo "<td>" . $staff['emp_id'] . "</td>";
                                echo "<td>" . $staff_full_name . "</td>";
                                echo "<td>" . ucwords(str_replace('_', ' ', strtolower($staff['job_role']))) . "</td>";
                                
                                if(!empty($staff['assigned_projects'])) {
                                    echo '<td>Assigned to: ' . $staff['assigned_projects'] . '</td>';
                                } else {
                                    echo '<td style="color: #26b82d;">Available</td>';
                                }

                                echo "<td>
                                    <div class='dropdown'>
                                        <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton{$s_index}' data-bs-toggle='dropdown' aria-expanded='false'>
                                            Actions
                                        </button>
                                        <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton{$s_index}'>
                                        <li><a class='dropdown-item remove-action' href='remove_employee_from_project.php?emp_id={$staff['emp_id']}&project_ids={$staff['assigned_projects']}'>Remove</a>
                                        </li>
                                        </ul>
                                    </div>
                                </td>";

                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="member-add-container" class="project-form-container">
                    <div class="project-form-content">
                        <h3>Member Assignments</h3>
                        <div class="divider"></div>
                        <form id="assign-project-form" action="assign_employee_project.php" method="post">
                            <div class="row align-items-center">
                                <div class="col-md">
                                    <label for="choose-project">Choose Project:</label>
                                    <select id="choose-project" name="project_id" required>
                                        <option value=""></option>
                                        <?php
                                            foreach ($projects as $index => $project) {
                                                echo "<option value='{$project['project_id']}'>#{$project['project_id']} - {$project['project_name']}</option>";
                                            }
                                        ?>
                                        <!-- Add more options here -->
                                    </select>
                                </div>
                                <div class="col-md">           
                                    <label for="choose-member">Choose Member:</label>
                                    <select id="choose-member" name="emp_id" required>
                                        <option value=""></option>
                                        <?php 
                                            foreach ($stafflist as $staff) {
                                                $staff_full_name = $staff['first_name'] . ' ' . $staff['last_name'];
                                                echo "<option value='{$staff['emp_id']}' data-jobrole='{$staff['job_role']}' data-staffname='{$staff_full_name}'>#{$staff['emp_id']} - {$staff_full_name} (" . ucwords(str_replace('_', ' ', strtolower($staff['job_role']))) . ")</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="job_role" name="job_role">
                            <input type="hidden" id="staff_name" name="staff_name">
                            <div class="row align-items-end">
                                <div class="col-md-15">
                                    <button class="assign-button">Assign</button>
                                </div>
                            </div>
                        </form>                    
                    </div>                
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
    $(document).ready(function () {
        $('#choose-member').change(function () {
            var selectedOption = $(this).find('option:selected');
            var jobRole = selectedOption.attr('data-jobrole');
            var staffFullName = selectedOption.attr('data-staffname');
            $('#job_role').val(jobRole);
            $('#staff_name').val(staffFullName);
        });
    });


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
 function getanswer() {
    fetch('update_progress.php')
    .then(response => response.json())
    .then(data => {
	if (data.status === 'success') {
	    // Access the percentage completed from the JSON response
	    const percentageCompleted = data.percentage_completed;
	    
	    // Use the percentage completed as needed
	    console.log('Percentage completed: ' + percentageCompleted);
	} else {
	    // Handle error case
	    console.error('Error: ' + data.message);
	}
    })
    .catch(error => {
	console.error('Error fetching data:', error);
    });
 }

    

    document.addEventListener("DOMContentLoaded", function () {
        //invite to register popup script
        document.getElementById('invite-link').addEventListener('click', openPopup);

	document.querySelectorAll('.progressBar').forEach(item => {
	    const projectId = item.getAttribute('data-project-id');
	    var xhr = new XMLHttpRequest();
	    var url = 'update_progress.php';
	    var params = 'project_id=' + projectId;
	
	    xhr.open('POST', url, true);
	    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	
	    xhr.onreadystatechange = function() {
		if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
		    console.log(xhr.responseText); // Log the response when the request is complete
		}
	    };
	    xhr.send(params);
	});

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

        //create button scripts
        const createProjectButton = document.getElementById("create-project-button");
        const projectFormContainer = document.getElementById("project-form-container");

        createProjectButton.addEventListener("click", () => {
            projectFormContainer.style.display = "flex";
            shadow_effect.style.display = "flex"; 
        });

        projectFormContainer.addEventListener("click", (e) => {
            if (e.target === projectFormContainer) {
                projectFormContainer.style.display = "none";
                shadow_effect.style.display = "none";
            }
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

        //assign member button scripts
        const addMemberButton = document.getElementById("member-add-button");
        const addMemberFormContainer = document.getElementById("member-add-container");

        addMemberButton.addEventListener("click", () => {
            addMemberFormContainer.style.display = "flex";
            shadow_effect.style.display = "flex"; 
        });


        addMemberFormContainer.addEventListener("click", (e) => {
            if (e.target === addMemberFormContainer) {
                addMemberFormContainer.style.display = "none";
                shadow_effect.style.display = "none";
            }
        });

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

    function updateSelectedManager() {
        var managerSelect = document.getElementById("create_manager_select");
        var selectedManager = managerSelect.options[managerSelect.selectedIndex].value;
        document.getElementById("selectedManager").value = selectedManager;
    }

    function updateSelectedEmployee(){
        var employeeSelect = document.getElementById("create_emp_select");
        var selectedEmployee = employeeSelect.options[employeeSelect.selectedIndex].text;
        document.getElementById("selectedEmployee").value = selectedEmployee;
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
        inviteCodeInput.value = "http://35.246.76.223/team-projects-part-2-team-20/register.php?invitecode=" + inviteCode; 
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
