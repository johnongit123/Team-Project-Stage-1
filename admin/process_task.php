<?php
// Check if the task ID was received via POST
if(isset($_POST['task_id'])) {
    // Retrieve the task ID from the POST parameters
    $taskId = $_POST['task_id'];

    // Establish a connection to your database
    $servername = "localhost";
    $username = "team020";
    $password = "YbiA4kgeNKEPrLNqFPap";
    $dbname = "team020";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "UPDATE task
            SET status = 'Done', complete = 'Y'
            WHERE task_id = ?";

    // Prepare the SQL statement using a prepared statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("i", $taskId); // Assuming task_id is an integer

    // Execute the statement
    if ($stmt->execute()) {
        echo "Task updated successfully";
    } else {
        echo "Error updating task: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the task ID was not received, send an error response
    echo "Error: Task ID not received.";
}
?>
