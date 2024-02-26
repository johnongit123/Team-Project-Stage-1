<?php
require_once '../includes/session-config.php';
require_once '../includes/dbh.php'; // Include your database connection file

// Check if the project ID is provided
if (isset($_GET['id'])) {
    global $con;

    $task_id = $_GET['id'];

    $update_assigns_query= "UPDATE assigns SET task_id = NULL WHERE task_id = ?";

    $stmt_update_assigns = mysqli_prepare($con, $update_assigns_query);
    $stmt_update_assigns->bind_param('i', $task_id);
    if (!$stmt_update_assigns->execute()) {
        // Handle any errors that occur during the update process
        echo "Error updating assigns: " . $stmt_update_assigns->error;
        exit(); // Exit the script if an error occurs
    }

    $stmt_update_assigns->close();


    // Construct the SQL query to delete the project
    $query = "DELETE FROM task WHERE task_id = ?";


    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('i', $task_id);
    if($stmt->execute()){
        $_SESSION["success_del"] = "Task has been deleted successfully!";
        header("Location: tasks.php");
        die();
    } else {
        // Handle any errors that occur during the deletion process
        echo "Error deleting project: " . $stmt->error;
    }

    $stmt->close();
}

function success_del(){
    if (isset($_SESSION["success_del"])){
        $success_message= $_SESSION["success_del"];
        unset($_SESSION["success_del"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}