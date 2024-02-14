<?php

require_once 'dbh-inc.php';
// Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'database_name');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random 3-digit number
function generateRandomNumber() {
    return rand(100, 999);
}

// Function to generate a unique project code
function generateProjectCode($project_id) {
    return $project_id . generateRandomNumber();
}

// Get data from the form
$project_name = $_POST['project_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Insert new project entry into the database
$sql = "INSERT INTO Projects (project_name, projectstartdate, projectenddate, project_code) VALUES ('$project_name', '$start_date', '$end_date', '')";
if ($conn->query($sql) === TRUE) {
    $project_id = $conn->insert_id;
    $project_code = generateProjectCode($project_id);
    // Update project entry with the generated project code
    $update_sql = "UPDATE Projects SET project_code = '$project_code' WHERE projectid = $project_id";
    $conn->query($update_sql);
    echo "New project created successfully with code: $project_code";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
