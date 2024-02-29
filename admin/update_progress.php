<?php
require_once '../includes/dbh.php';

if(isset($_POST['project_id'])) {
    // Retrieve the project ID from the POST request
    $projectId = $_POST['project_id'];
    
    // Now you can use $projectId as needed, for example:
    echo "Received project ID: " . $projectId;
    
    // SQL queries to count total tasks and completed tasks
    $sql = "SELECT COUNT(task_id) AS task_count FROM task WHERE project_id = ?";
    $sql2 = "SELECT COUNT(task_id) AS task_completed FROM task WHERE status = 'Done' AND project_id = ?";

    // Prepare and bind parameters for the first SQL query
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare and bind parameters for the second SQL query
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param("i", $projectId);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

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

    // Close the statements
    $stmt->close();
    $stmt2->close();
    
    // Update query
    $updatesql = "UPDATE project
            SET Progress = ?
            WHERE project_id = ?";
    
    // Prepare the statement
    $updatestmt = $con->prepare($updatesql);
    
    if ($updatestmt) {
        // Bind parameters and execute the statement
        $updatestmt->bind_param("ii", $percentageCompleted, $projectId);
    
        $updatestmt->execute();
    
        // Check if the update was successful
        if ($updatestmt->affected_rows > 0) {
            echo "Progress updated successfully.";
        } else {
            echo "No rows updated. Project ID might not exist or the progress value was the same.";
        }
    
        // Close the statement
        $updatestmt->close();
    } else {
        // Handle the case where the statement preparation failed
        echo "Error preparing statement: " . $con->error;
    }
} else {
    // If project_id is not set in the POST request
    echo "Error: project_id is not set in the POST request.";
}
// Close the database connection
$con->close();
?>






