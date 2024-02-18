<?php
require_once '../includes/session-config.php';
check_login();

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
            <p class="font-weight-bold">ADMIN VIEW</p>
        </div>
    <?php
$servername = "localhost";
$username = "team20";
$password = "FjE*onBcO3)8KAwt ";
$dbname = "team20";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start HTML output
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="styles.css"> 
    <script src="Test.js"></script>   
</head>
<body>
    <div class="top-bar">
        <h1>Threads</h1>
    </div>
    <div class="main">
        <ol>';

$sql = "SELECT ThreadID, title, author, date, content FROM Threads";
$result = $conn->query($sql);

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Generate HTML dynamically using fetched data
        echo '<li class="row">
                <a href="index.php/?id=' . $row["ThreadID"] . '">
                    <h4 class="title">' . $row["title"] . '</h4>
                    <div class="bottom">
                        <p class="timestamp">' . date("Y-m-d H:i:s", strtotime($row["date"])) . ' ' . $row["author"] . '</p>
                    </div>
                </a>
              </li>';
    }
} else {
    echo "0 results";
}
 
echo '<div class="container">
    <h2>Create New Thread</h2>
    <form method="post" enctype="multipart/form-data" action="Forum/index.php">
        <p for="thread_title"><strong>Thread Title:</strong></p>
        <input type="text" id="thread_title" name="thread_title" required><br>

        <p for="author_name"><strong>Your Name:</strong></p>
        <input type="text" id="author_name" name="author_name" required><br>

        <label for="thread_content"><strong>Thread Content:</strong></label><br>
        <textarea id="thread_content" name="thread_content" rows="4" cols="50" required></textarea><br>


        <input type="submit" id="create_thread_button" value="Create Thread">
    </form>
</div>';





if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $threadTitle = $_POST["thread_title"];
    $authorName = $_POST["author_name"];
    $threadContent = $_POST["thread_content"];



    $sqlAdd = "INSERT INTO Threads (Title, Author, Date, Content)
               VALUES ('$threadTitle', '$authorName', NOW(), '$threadContent')";

    // Execute SQL query
    if ($conn->query($sqlAdd) === TRUE) {
        echo 'New Record created Succesfully';
    } else {
        echo "Error: " . $sqlAdd . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}





// End HTML output
echo '</ol>
    </div>
</body>
</html>';
?>



 
        
    </main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="../js/sidebar_scripts.js"></script>
<script>
    

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('invite-link').addEventListener('click', openPopup);
    });

    const shadow_effect = document.getElementById("shadow-effect");
    
    //Invitation scripts
    // Function to open the popup

    function openPopup() {
        var popup = document.getElementById('invite-popup');
        var inviteCodeInput = document.getElementById('invite-link-input');
        var inviteCode = generateInviteCode();
        inviteCodeInput.value = "http://localhost:3000/Music/TeamProject/register.php?invitecode=" + inviteCode; //MATCH TO VIRTUALMACHINE
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
