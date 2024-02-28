<?php
// Check if the task ID was received via POST
if(isset($_POST['task_id'])) {
    require_once '../includes/dbh.php';
    // Retrieve the task ID from the POST parameters
    $taskId = $_POST['task_id'];

    // Prepare the SQL statement
    $sql = "UPDATE task
            SET status = 'Done', complete = 'Y'
            WHERE task_id = ?";

    // Prepare the SQL statement using a prepared statement
    $stmt = $con->prepare($sql);

    // Bind parameters
    $stmt->bind_param("i", $taskId); // Assuming task_id is an integer

    // Execute the statement
    if ($stmt->execute()) {
        echo "Task updated successfully";
    } else {
        echo "Error updating task: " . $con->error;
    }

    // Close statement and connection
    $stmt->close();
} else {
    // If the task ID was not received, send an error response
    echo "Error: Task ID not received.";
}
?>
