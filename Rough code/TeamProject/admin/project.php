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
                    <a href="tasks.html">
                        <span class="material-symbols-outlined">task</span>
                        <span class="text">Tasks</span>
                        <span class="tooltip">Tasks</span>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="team.html">
                        <span class="material-symbols-outlined">groups</span>
                        <span class="text">Teams</span>
                        <span class="tooltip">Teams</span>
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
                    <a href="faq.html">
                        <span class="material-symbols-outlined">contact_support</span>
                        <span class="text">FAQ</span>
                        <span class="tooltip">FAQ</span>
                    </a>
                </li>
                <li class="sidebar-list-item logoutMenu">
                    <a href="#" id="logoutLink">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="text">Logout</span>
                        <span class="tooltip">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <!--Small window shadow effect-->
        <div class="overlay"></div>

        <!--Main Content-->
        <main class="main-container">
            <div class="main-title">
                <p class="font-weight-bold">ADMIN PROJECT VIEW</p>
            </div>

            <div class="charts-card big-chart single-view">
                <div class="chart-header">
                    <p class="chart-title">Projects</p>
                    <div class="create-project-button">
                        <button id="create-project-button" class="create-project-button">Create Project</button>
                    </div>
                </div>
                <div class="chart-body">
                    <table class="table table-hover table-condensed" id="list">
                        <colgroup>
                            <col width="5%">
                            <col width="35%">
                            <col width="15%">
                            <col width="15%">
                            <col width="10%">
                            <col width="20%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Project</th>
                                <th>Date Started</th>
                                <th>Due Date</th>
                                <th>Duration</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    <tbody><?php
                    require_once '../includes/dbh.php';

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
                            echo "<td>" . $project['project_name'] . "</td>";
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
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="project-form-container" class="project-form-container">
            <div class="project-form-content">
                <h2>Create Project</h2>
                <form id="project-form"  action="commands/new_project.php" method="post">
                    <label for="">Project Name:</label>
                    <input type="text" class="form-control form-control-sm" name="subject" value="<?php echo isset($subject) ? $subject : '' ?>" required>
                    <label for="">Status</label>
					<select name="status" id="status">
                        <?php
                        $stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
                        foreach ($stat as $value) {
                            echo "<option value=\"$value\">$value</option>";
                        }
                        ?>
                    </select>
                    
                    
                    <label for="">Date</label>

                    <input type="date" class="form-control form-control-sm" name="date" value="<?php echo isset($date) ? date("Y-m-d",strtotime($date)) : '' ?>" required>
                    <label for="">Start Time</label>
                    <input type="date" class="form-control form-control-sm" name="start_time" value="<?php echo isset($start_time) ? date("H:i",strtotime("2020-01-01 ".$start_time)) : '' ?>" required>
                    <label for="">End Date</label>
                    <input type="time" id="end-date" name="end_time" required>
                </form>
                        <?php
                            success_message();
                            check_project_errors();
                        ?>
                        
            </div>
        </main>



<script src="../js/sidebar_scripts.js"></script>
<script>
    const createProjectButton = document.getElementById("create-project-button");
    const projectFormContainer = document.getElementById("project-form-container");

    // Function to show the form pop-up
    const showForm = () => {
        projectFormContainer.style.display = "block";
    };

    // Function to hide the form pop-up
    const hideForm = () => {
        projectFormContainer.style.display = "none";
    };

    // Event listener for the button click to show the form
    createProjectButton.addEventListener("click", (event) => {
        event.stopPropagation(); // Prevent click event from bubbling to the container
        showForm();
    });

    // Event listener for clicks on the form container
    projectFormContainer.addEventListener("click", (event) => {
        // If the click happened outside the form itself, hide the form
        if (event.target === projectFormContainer) {
            hideForm();
        }
    }); 
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>