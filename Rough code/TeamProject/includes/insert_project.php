<?php 
include "dbh-inc.php";

$conn = new mysqli($servername, $db_username, $db_pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$project_name = $_POST['project_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Prepare SQL statement to insert project
$stmt = $conn->prepare("INSERT INTO projects (project_name, start_date, end_date) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $project_name, $start_date, $end_date);

// Execute the statement
if ($stmt->execute() === TRUE) {
    echo "New project created successfully";
} else {
    echo "Error: " . $conn->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>