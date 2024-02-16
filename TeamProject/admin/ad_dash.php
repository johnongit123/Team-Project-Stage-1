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
    <link rel="stylesheet" href="../css/admin_styles.css">
    <link rel="stylesheet" href="../css/sidebar_styles.css">
    <!-- FullCalendar CSS -->
    <link href='https://fullcalendar.io/releases/fullcalendar/3.9.0/fullcalendar.min.css' rel='stylesheet' />
</head>  
<body>
        <!--Header-->
        <div class="header">
            <div class="header-left">
                <span class="material-icons-outlined">search</span>
            </div>
            <div class="header-right">
                <span class="material-icons-outlined">notifications</span>
                <span class="material-icons-outlined">email</span>
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
                    <a href="ad_dash.html">
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
                    <a href="../includes/logout.php" id="logoutLink">
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
                <p class="font-weight-bold">ADMIN DASHBOARD</p>
            </div>
            <!--Part 1-->
            <div class="admin-dash">

                <div class="charts-card">
                    <div id="calendar"></div>
                </div>



                <div class="charts-card">
                    <p class="chart-title">Urgent Tasks</p>
                    <div class="divider"></div>
                    <div id="urgent-task-chart" class="urgent-task-chart">
                        <div class="urgent-task-item">
                            <span class="material-symbols-outlined">fiber_manual_record</span>
                            <p>Task 1</p>
                        </div>
                        <div class="divider"></div>
                        <div class="urgent-task-item">
                            <span class="material-symbols-outlined">fiber_manual_record</span>
                            <p>Task 2</p>
                        </div>
                        <div class="divider"></div>
                        <div class="urgent-task-item">
                            <span class="material-symbols-outlined">fiber_manual_record</span>
                            <p>Task 3</p>
                        </div>
                    </div>  
                </div>

                <div class="charts-card">
                    <p class="chart-title">Project Directory</p>
                    <div class="divider"></div>
                    <div id="project-dir-chart" class="project-dir-chart">
                        <div class="project-dir-item">
                            <span class="material-symbols-outlined">equalizer</span>
                            <p>Project 1</p>
                        </div>
                        <div class="divider"></div>
                        <div class="project-dir-item">
                            <span class="material-symbols-outlined">equalizer</span>
                            <p>Project 2</p>
                        </div>
                        <div class="divider"></div>
                        <div class="project-dir-item">
                            <span class="material-symbols-outlined">equalizer</span>
                            <p>Project 3</p>
                        </div>
                    </div>  
                </div>
        
                <div class="charts-card custom-card">
                    <p class="chart-title">Messages</p>
                    <div class="divider"></div>
                    <div id="messages-chart" class="message-previews">
                        <div class="message-preview">
                            <div class="sender-avatar">
                                <img src="img/white.jpeg" alt="Sender 1">
                            </div>
                            <div class="message-content">
                                <p class="sender-name">John Arsne</p>
                                <p class="message-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            </div>
                            
                        </div>
                        <div class="divider"></div>
                        
                        <div class="message-preview">
                            
                            <div class="sender-avatar">
                                <img src="img/female.webp" alt="Sender 2">
                            </div>
                            <div class="message-content">
                                <p class="sender-name">Anne Fold</p>
                                <p class="message-text">Nulla facilisi. Vivamus condimentum urna a orci auctor, et accumsan velit bibendum.</p>
                            </div>
                        </div>
                        <div class="divider"></div>
                        
                        <div class="message-preview">
                            
                            <div class="sender-avatar">
                                <img src="img/female2.jpeg" alt="Sender 2">
                            </div>
                            <div class="message-content">
                                <p class="sender-name">Jane Fill</p>
                                <p class="message-text">Nulla facilisi. Vivamus condimentum urna a orci auctor, et accumsan velit bibendum.</p>
                            </div>
                        </div>
                <div class="divider"></div>
                        <div class="response">
                            <div class="response-input-container">
                                <input type="text" placeholder="Type your response..." class="response-input">
                                <button class="response-send-btn">Enter</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="charts-card">
                    <p class="chart-title">Team Members</p>
                    <div class="divider"></div>
                    <div id="team-chart" class="team-members">
                        <div class="team-member">
                            <div class="team-member-avatar">
                                <img src="img/female3.jpeg" alt="Team Member 1">
                            </div>
                            <p class="team-member-name">Alice Malice</p>
                        </div>
                        <div class="team-member">
                            <div class="team-member-avatar">
                                <img src="img/white.jpeg" alt="Team Member 2">
                            </div>
                            <p class="team-member-name">Bob Thorne</p>
                        </div>
                        <div class="team-member">
                            <div class="team-member-avatar">
                                <img src="img/male3.jpeg" alt="Team Member 3">
                            </div>
                            <p class="team-member-name">Charlie Hold</p>
                        </div>
                        <div class="team-member">
                            <div class="team-member-avatar">
                                <img src="img/male4.webp" alt="Team Member 1">
                            </div>
                            <p class="team-member-name">Jack Mason</p>
                        </div>
                        <div class="team-member">
                            <div class="team-member-avatar">
                                <img src="img/male5.webp" alt="Team Member 2">
                            </div>
                            <p class="team-member-name">Sonny Hill</p>
                        </div>
                        <div class="team-member">
                            <div class="team-member-avatar">
                                <img src="img/male2.webp" alt="Team Member 3">
                            </div>
                            <p class="team-member-name">Chris King</p>
                        </div>
                        <div class="manage-members">
                            <button class="green">View All</button>
                            <button class="blue">Add Member</button>
                            <button class="red">Remove Member</button>
                        </div>
                    </div>                    
                </div>


                <div class="charts-card responsive-tab">
                    <p class="chart-title">Progress of Projects</p>
                    <div class="divider"></div>
                    <div class="progress-chart" id="progress-chart">
                        <ul class="progress-list">
                            <li>Project 1
                                <div class="progress-bar">
                                    <div class="progress-bar-inner" style="width: 60%;"></div>
                                </div>
                                <small>60% Completed</small>
                            </li>
                            <li>Project 2
                                <div class="progress-bar">
                                    <div class="progress-bar-inner" style="width: 40%;"></div>
                                </div>
                                <small>40% Completed</small>
                            </li>
                        </ul>
                    </div>
                </div>
   
            </div>
        </main>
    

<!--JS-->
<script src="js/sidebar_scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<!-- FullCalendar JS and dependencies -->
<script src='https://fullcalendar.io/releases/fullcalendar/3.9.0/lib/jquery.min.js'></script>
<script src='https://fullcalendar.io/releases/fullcalendar/3.9.0/lib/moment.min.js'></script>
<script src='https://fullcalendar.io/releases/fullcalendar/3.9.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            height: 230,
            contentHeight: 230,
            defaultView: 'month',
            events: [{
                title: 'Meeting',
                start: '2023-10-10'
                },
                {
                    title: 'Task Deadline',
                    start: '2023-10-15'
                }
            ],
            eventColor: '#4CAF50'
        });
    });
</script>
</body>
</html>