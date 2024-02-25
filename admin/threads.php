<?php
require_once '../includes/session-config.php';
check_login();
require_once '../includes/dbh.php';
require_once 'new_threads.php';
require_once 'delete_threads.php';
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
            <p class="font-weight-bold">KNOWLEDGE MANAGEMENT</p>
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

        <div class="charts single-view big-chart">
            <div class="charts-card">
            <div class="chart-header">
                <p class="chart-title">Threads</p>
                <div class="create-thread-button">
                    <button id="create-thread-button">New Thread</button>
                </div>
            </div>
            <div class="divider"></div>
            <?php
                success_thread();
                success_delete();
                check_thread_errors();
            ?>
            <div class="chart-list">              
                <table class="table table-hover">
                    <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thread</th>
                            <th>Author</th>
                            <th>Date Opened</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="thread-tdbody">
                        <?php
                        $sql = "SELECT * FROM Threads ORDER BY date";
                        $result = $con->query($sql);

                        $threads = [];

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $threads[] = $row;
                            }
                        } else {
                            echo "<p class='form-error'>0 results</p>";
                        }

                        foreach ($threads as $index => $thread){
                            echo "<tr>";
                            echo "<td>" . $thread['thread_id'] . "</td>";
                            echo "<td><p class='title_name'>" . $thread['title'] . "</p></td>";
                            echo "<td>" . $thread['author'] . "</td>";
                            echo "<td>" . $thread['date'] . "</td>";
                            echo "<td>
                            <div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton{$index}' data-bs-toggle='dropdown' aria-expanded='false'>
                                    Actions
                                </button>
                                <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton{$index}'>
                                    <li><a class='dropdown-item delete-action' href='delete_threads.php?id={$thread['thread_id']}'>Delete</a></li>
                                </ul>
                            </div>
                        </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="create-thread-container" class="project-form-container">
                <div class="project-form-content">
                    <h2>Create Thread</h2>
                    <div class="divider"></div>
                    <form id="thread-form" action="new_threads.php" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_thread_name">Thread Name:</label>
                                    <input type="text"  id="create_thread_name" name="thread_name" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_author_name">Your Name:</label>
                                    <input type="text"  id="create_author_name" name="author_name" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-16">
                                <div class="form-group">
                                    <label for="create_content" class="control-label">Thread Content:</label>
                                    <textarea name="content" id="create_content" cols="30" rows="10" class="form-control"></textarea>
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
            <div class="contents-container" id="contents-container">
                <div class="contents-content">
                    <p id="contents-text"></p>
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


        const contentData = <?php echo json_encode($threads); ?>;
        
        
        const rows = document.querySelectorAll("tbody#thread-tdbody tr");
        rows.forEach(row => {
            const titleNameElement = row.querySelector('.title_name');
            
            titleNameElement.addEventListener("click", () => {
                const titleName = titleNameElement.textContent;
                const thread = contentData.find(thread => thread.title === titleName);

                if (thread) {
                    const content = thread.content;
                    const contentText = document.getElementById("contents-text");
                    contentText.innerHTML = `<h2>${titleName}</h2><div class="divider"></div><p>${content}</p>`;
                
                    openDescription();
                }
            });
        });
    
        contentsContainer.addEventListener("click", (e) => {
            if (e.target === contentsContainer) {
                    closeDescription();
                }
        });

        const createThreadButton = document.getElementById("create-thread-button");
        const createThreadContainer = document.getElementById("create-thread-container");
        
        createThreadButton.addEventListener("click", () => {
            createThreadContainer.style.display = "flex";
            shadow_effect.style.display = "flex"; 
        });

        createThreadContainer.addEventListener("click", (e) => {
            if (e.target === createThreadContainer) {
                createThreadContainer.style.display = "none";
                shadow_effect.style.display = "none";
            }
        });
    });

    const shadow_effect = document.getElementById("shadow-effect");
    const contentsContainer = document.getElementById("contents-container");
    
    function openDescription() {
        contentsContainer.style.display = "flex";
        shadow_effect.style.display = "flex";
    }
    
    function closeDescription() {
        contentsContainer.style.display = "none";
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
