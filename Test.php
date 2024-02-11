<?php
$servername = "Localhost"; // Change this to your MySQL server address
$username = "team20"; // Change this to your MySQL username
$password = "password"; // Change this to your MySQL password
$database = "Team20"; // Change this to the name of your MySQL database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->    
</head>

    <div class="top-bar">
        <h1>
            My Forum
        </h1>
    </div>
    <div class="main">
        <ol>  
            <li class ="row">
                <a href="/thread.html?id=${thread.id}">
                    <h4 class = "title">
                        ${thread.title} 
                    </h4>
                    <div class = "bottom">
                        <p class = "timestamp">
                            ${new Date(thread.date).toLocaleString()}
                        </p>
                        <p class="comment-count">
                             _${thread.comments.length} comments
                        </p>
                    </div>
                </a>

        </ol>
    </div>

    <script>
