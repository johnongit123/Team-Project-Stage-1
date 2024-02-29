<?php
require_once '../includes/session-config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['emp_id'])) {
    $taskId = $_POST['task_id'] ?? '';
    $empId = $_SESSION['emp_id'];

    try {
        require_once '../includes/dbh.php';

        // Update task status to 'complete' in the database
        update_td_task_status($taskId, $empId);
        header("Location: project.php");
        die();
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function update_td_task_status($taskId, $empId){
    global $con;
    $status = "Completed";
    $query = "UPDATE tdl SET status = ? WHERE tdl_id = ? AND emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sii", $status, $taskId, $empId);
    $stmt->execute();
    $stmt->close();
}
