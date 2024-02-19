<?php
require_once '../includes/session-config.php';
$memberID = check_login();
require_once '../includes/dbh.php';
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
                <a href="faq.php">
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
            <p class="font-weight-bold">EMPLOYEE VIEW</p>
        </div>
        
        <div class="charts single-view big-chart">
            <div class="charts-card">
                <!--Card header portion-->
                <div class="chart-header">
                    <p class="chart-title">PROJECTS LIST</p>
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
                                <th>Manager</th>
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
                                    echo "<tr><td colspan='7'><p class='form-error' style='margin-top: 10px;'>No Projects have been assigned</p></td></tr>";
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
                                    echo "<td>" . ($project['manager_name'] ? $project['manager_name'] : '-') . "</td>"; 
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
        
        

        
    });

   
    
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
