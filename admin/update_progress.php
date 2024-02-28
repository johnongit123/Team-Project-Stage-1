<?php
require_once '../includes/dbh.php';

// Retrieve the project ID from the POST request
$projectId = $_POST['project_id'];

// Now you can use $projectId as needed, for example:
echo "Received project ID: " . $projectId;

$sql = "SELECT COUNT(task_id) AS task_count FROM task WHERE project_id = $projectId";
$sql2 = "SELECT COUNT(task_id) AS task_completed FROM task WHERE status = 'Done' AND project_id = $projectId";

// Execute the SQL queries
$result = $con->query($sql);
$result2 = $con->query($sql2);

// Check if the queries executed successfully
if ($result && $result2) {
    // Check if there are rows returned by the queries
    if ($result->num_rows > 0 && $result2->num_rows > 0) {
        // Fetch the result rows as associative arrays
        $row = $result->fetch_assoc();
        $row2 = $result2->fetch_assoc();
        
        // Access the counts of task IDs
        $taskCount = $row['task_count'];
        $taskCompletedCount = $row2['task_completed'];
        
        // Calculate the percentage of completed tasks
        if ($taskCount > 0) {
            $percentageCompleted = ($taskCompletedCount / $taskCount) * 100;
            echo "Percentage of completed tasks: $percentageCompleted%";
        } else {
            echo "No tasks found for project ID $projectId";
        }
    } else {
        echo "No tasks found for project ID $projectId";
    }
} else {
    // Handle the case where the queries fail
    echo "Error executing query: " . $con->error;
}

// Update query
$sql = "UPDATE project
        SET Progress = ?
        WHERE project_id = ?";

// Prepare the statement
$stmt = $con->prepare($sql);

if ($stmt) {
    // Bind parameters and execute the statement
    $stmt->bind_param("ii", $percentageCompleted, $projectId);

    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Progress updated successfully.";
    } else {
        echo "No rows updated. Project ID might not exist or the progress value was the same.";
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle the case where the statement preparation failed
    echo "Error preparing statement: " . $con->error;
}

// Close the database connection
$con->close();
?>





