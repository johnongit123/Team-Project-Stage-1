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



 
