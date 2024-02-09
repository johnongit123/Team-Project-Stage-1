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