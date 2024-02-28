<?php
session_start();
include('./index.php'); // Include your database connection script

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['emp_id'])) {
    $taskId = $_POST['task_id'] ?? '';
    $status = $_POST['status'] ?? '';
    

    // Update the task status in the database
    $stmt = $conn->prepare("UPDATE to_do_list SET status = ? WHERE task_id = ? AND emp_id = ?");
    $stmt->bind_param("sii", $status, $taskId, $_SESSION['emp_id']);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Task status updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update task status']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request or user not logged in']);
}
?>
