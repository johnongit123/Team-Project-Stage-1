<?php
require_once '../includes/session-config.php';
require_once '../includes/dbh.php';
require_once 'new_project.php';
require_once 'assign_employee_project.php';
require_once 'remove_employee_from_project.php';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
            <p class="font-weight-bold">Test</p>
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
                <a href="ad_dash.php">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text">Dashboard</span>
                    <span class="tooltip">Dashboard</span>
                </a>
            </li>
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
                <a href="#">
                    <span class="material-symbols-outlined">contact_support</span>
                    <span class="text">FAQ</span>
                    <span class="tooltip">FAQ</span>
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
            <p class="font-weight-bold">ADMIN PROJECT VIEW</p>
        </div>
        
        <div class="charts single-view">
            <div class="charts-card">
                <!--Card header portion-->
                <div class="chart-header">
                    <p class="chart-title">PROJECTS LIST</p>
                    <div class="create-project-button">
                        <button id="create-project-button">Create Project</button>
                    </div>
                    <div class="edit-project-button">
                        <button id="edit-project-button">Edit Project</button>
                    </div>
                </div>
                <div class="divider"></div>
                <?php
                    success_message();
                    check_project_errors();
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
                            <col width="20%">
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
                        <tbody class="project-tdbody">
                            <!--All the project tables-->
                            <?php
                            $sql = "SELECT * FROM project";
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
                                            <li><button id='edit-project-button'class='dropdown-item edit-action' data-project-details='" . htmlspecialchars(json_encode($project)) . "'>Edit</button></li>
                                        </ul>
                                    </div>
                                </td>";

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
                                        <label for="">Project Name:</label>
                                        <input type="text"  id="create_project_name" name="project_name" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="">Status:</label>
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
                                        <label for="">Start Date:</label>
                                        <input type="date" id="create_start_date" name="start_date" class="form-control form-control-sm" required>
                                    </div>
                                </div>

                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="">End Date:</label>
                                        <input type="date" id="create_end_date" name="end_date" class="form-control form-control-sm" name="start_time" required>
                                    </div>           
                                </div>                
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="selectedManager" name="selected_manager">
                                        <label for="" class="control-label">Project Manager:</label>
                                        <select name="manager_select" id="create_manager_select" onchange="updateSelectedManager()">
                                            <option disabled><option>
                                            <?php
                                            $tl_sql = "select employee.first_name, employee.last_name, assigns.project_id  from employee INNER JOIN assigns 
                                            ON employee.emp_id = assigns.emp_id where job_role = 'team_leader' AND is_reg = 'Y'";
                                            $tl_result = $con->query($tl_sql);

                                            $teamlead_lists = [];

                                            if ($tl_result->num_rows > 0) {
                                                // Fetch each row of the result as an associative array and store it in $projects
                                                while($tl_row = $tl_result->fetch_assoc()) {
                                                    $tl_fullname = $tl_row['first_name'].' '. $tl_row['last_name'];
                                                    $disabled = $tl_row['project_id'] !== null ? "disabled" : ""; // Check if project_id is not null
                                                    echo "<option value=\"" . htmlspecialchars($tl_fullname) . "\" $disabled>" . htmlspecialchars($tl_fullname) . "</option>";
                                                }
                                            } else {
                                                echo "<option class='form-error'>No Results</p>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <input type="hidden" id="selectedEmployee" name="selected_employees">
                                        <label for="" class="control-label">Team Members:</label>
                                        <select class="form-control form-control-sm" name="emp_select" id="create_emp_select" onchange="updateSelectedEmployee()">
                                            <option></option>
                                            <?php
                                            $emp_sql = "select employee.first_name, employee.last_name, assigns.project_id  from employee INNER JOIN assigns 
                                            ON employee.emp_id = assigns.emp_id where job_role = 'employee' AND is_reg = 'Y'";
                                            $emp_result = $con->query($emp_sql);

                                            $emp_lists = [];

                                            if ($emp_result->num_rows > 0) {
                                                // Fetch each row of the result as an associative array and store it in $projects
                                                while($emp_row = $emp_result->fetch_assoc()) {
                                                    $e_fullname = $emp_row['first_name'].' '. $emp_row['last_name'];
                                                    $disabled = $emp_row['project_id'] !== null ? "disabled" : "";
                                                    echo "<option value=\"" . htmlspecialchars($e_fullname) . "\" $disabled>" . htmlspecialchars($e_fullname) . "</option>";
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
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="" class="control-label">Description:</label>
                                        <textarea name="description" id="create_description" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class=" create-button btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--Edit form-->
                <div id="edit-project-container" class="project-form-container">
                    <div class="project-form-content">
                        <form id="edit-project-form" action="edit_project.php" method="post">
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
                                        <select name="status" id="edit_status" class="form-control form-control-sm">
                                            <!-- Options for status -->
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
                                        <textarea name="description" id="edit_description" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
                    check_remove_assign_errors();
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
                        <tbody class="employee-tdbody">
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
                                        <li><a class='dropdown-item remove-action' href='remove_employee_from_project.php?emp_id={$staff['emp_id']}&project_id={$staff['assigned_projects']}'>Remove</a></li>
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
                        <form id="assign-project-form" action="assign_employee_project.php" method="post">
                            <div class="row align-items-center">
                                <div class="col-md">
                                    <label for="choose-project">Choose Project:</label>
                                    <select id="choose-project" name="project_id">
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
                                    <select id="choose-member" name="emp_id">
                                        <?php 
                                            foreach ($stafflist as $staff) {
                                                $staff_full_name = $staff['first_name'] . ' ' . $staff['last_name'];
                                                echo "<option value='{$staff['emp_id']}'>#{$staff['emp_id']} - {$staff_full_name} (" . ucwords(str_replace('_', ' ', strtolower($staff['job_role']))) . ")</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row align-items-end">
                                <div class="col-lg-12">
                                    <button class="assign-button">Assign</button>
                                </div>
                            </div>
                        </form>                    
                    </div>                
                </div>
            </div>                              
        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="../js/sidebar_scripts.js"></script>
<script> 
    
    function updateSelectedManager() {
        var managerSelect = document.getElementById("create_manager_select");
        var selectedManager = managerSelect.options[managerSelect.selectedIndex].text;
        document.getElementById("selectedManager").value = selectedManager;
        console.log(selectedManager);
    }

    function updateSelectedEmployee(){
        var employeeSelect = document.getElementById("create_emp_select");
        var selectedEmployee = employeeSelect.options[employeeSelect.selectedIndex].text;
        document.getElementById("selectedEmployee").value = selectedEmployee;
        console.log(selectedEmployee);
    }



    document.addEventListener("DOMContentLoaded", function () {
        const shadow_effect = document.getElementById("shadow-effect");
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

        function closeMemberAddForm() {
            document.getElementById("member-add-form-container").style.display = "none";
            shadow_effect.style.display = "none";
        }

        

        
        const editProjectButton = document.getElementById("edit-project-button");
        const editButtonAction = document.querySelectorAll(".edit-action");
        const editFormContainer = document.getElementById("edit-project-form-container");


        editButtonAction.forEach(editProjectButton => { 
            editProjectButton.addEventListener("click", () => {
                const projectDetails = JSON.parse(button.dataset.projectDetails);
                console.log(projectDetails);

                document.getElementById("project_name").value = projectDetails.project_name;
                document.getElementById("status").value = projectDetails.status;
                document.getElementById("start_date").value = projectDetails.start_date;
                document.getElementById("end_date").value = projectDetails.end_date;
                document.getElementById("description").value = projectDetails.description;

                // Show the edit project form
                editFormContainer.style.display = "flex";
                shadow_effect.style.display = "flex"; 
                
            });
        });

        
        const descriptionContainer = document.getElementById("description-container");
        const descriptionText = document.getElementById("description-text");
        const descriptionData = <?php echo json_encode($projects); ?>;


        descriptionContainer.addEventListener("click", (e) => {
        // If the click is not on the description content, close the description container
        if (e.target === descriptionContainer) {
                closeDescription();
                
            }
        });
        
        const rows = document.querySelectorAll("tbody.project-tdbody tr");
        rows.forEach(row => {
            const projectNameElement = row.querySelector('.proj_name');
            
            projectNameElement.addEventListener("click", () => {
                event.stopPropagation(); // ensuring the parent elements arent affected by click

                const projectName = projectNameElement.textContent;
                const project = descriptionData.find(proj => proj.project_name === projectName);
                const description = project ? project.description : '';

                descriptionText.innerHTML = `<h2>${projectName}</h2><div class="divider"></div><p>${description}</p>`;
                openDescription();
            });
        });

        function openDescription() {
            descriptionContainer.style.display = "flex";
            shadow_effect.style.display = "flex";
        }

        function closeDescription() {
            descriptionContainer.style.display = "none";
            shadow_effect.style.display = "none";
        }

        
    });
</script>
</body>
</html>