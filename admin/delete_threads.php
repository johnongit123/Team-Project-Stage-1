<?php
require_once '../includes/session-config.php';

// Check if the project ID is provided
if (isset($_GET['id'])) {
    require_once '../includes/dbh.php';
    global $con;

    $thread_id = $_GET['id'];

    // Construct the SQL query to delete the thread
    $query = "DELETE FROM Threads WHERE thread_id = ?";

    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('i', $thread_id);

    if($stmt->execute()){
        $_SESSION["success_delete"] = "Thread has been deleted successfully!";
        header("Location: threads.php");
        die();
    } else {
        // Handle any errors that occur during the deletion process
        echo "Error deleting thread: " . $stmt->error;
    }

    $stmt->close();
}

function success_delete(){
    if (isset($_SESSION["success_delete"])){
        $success_message= $_SESSION["success_delete"];
        unset($_SESSION["success_delete"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

