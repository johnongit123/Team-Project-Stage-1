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
    <link rel="stylesheet" href="../css/staff_styles.css">
    <link rel="stylesheet" href="../css/sidebar_styles.css">

</head>  
<body>
    
    <!--Header-->
        <div class="header">
            <div class="header-left">
                <p class="font-weight-bold">DASHBOARD</p>
            </div>
            <div class="header-right">
                <span class="material-icons-outlined">notifications</span>
                <div class="popup">

                </div>
                <span class="material-icons-outlined">account_circle</span>
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
                    <a href="emp_dash.php">
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
                    <a href="messages.html">
                        <span class="material-symbols-outlined">chat</span>
                        <span class="text">Messages</span>
                        <span class="tooltip">Messages</span>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="project.html">
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
                    <a href="includes/logout.php" id="logoutLink">
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
                <p class="font-weight-bold"> EMPLOYEE VIEW</p>
            </div>
            <div class="charts">
                <!--To do list card -->
                <div class="charts-card">
                    <p class="chart-title">To-Do List</p>
                    <div class="todo-app">
                        <div class="row">
                            <input type="text" id="input-box" placeholder="Add your text">
                            <button onclick="addTask()">Add</button>
                        </div>
                        <ul id="list-container">
                            <!--<li class="checked">Make coffee or tea</li>
                            <li>Respond to urgent and important emails</li>
                            <li>Complete Task 1 related to project A</li>-->
                        </ul>
                    </div>
                </div>

                <!--Task view card -->
                <div class="charts-card">
                    <p class="chart-title">Tasks</p>
                    <div id="task-chart" class="task-chart">
                        <div class="task-item">
                            <span class="material-symbols-outlined">fiber_manual_record</span>
                            <p>Task 1</p>
                        </div>
                        <div class="divider"></div>
                        <div class="task-item">
                            <span class="material-symbols-outlined">fiber_manual_record</span>
                            <p>Task 2</p>
                        </div>
                        <div class="divider"></div>
                        <div class="task-item">
                            <span class="material-symbols-outlined">fiber_manual_record</span>
                            <p>Task 3</p>
                        </div>
                    </div>  
                </div>
                

                <!--Project View Card -->
                <div class="charts-card">
                    <p class="chart-title">Projects</p>
                    <div id="project-chart" class="project-chart">
                        <div class="project-item">
                            <span class="material-symbols-outlined">equalizer</span>
                            <p>Project 1</p>
                        </div>
                        <div class="divider"></div>
                        <div class="project-item">
                            <span class="material-symbols-outlined">equalizer</span>
                            <p>Project 2</p>
                        </div>
                        <div class="divider"></div>
                        <div class="project-item">
                            <span class="material-symbols-outlined">equalizer</span>
                            <p>Project 3</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<!--JS-->
<script src="../js/sidebar_scripts.js"></script>
<script src="../js/to-do-list-script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>