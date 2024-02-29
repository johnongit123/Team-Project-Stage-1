<?php
require_once '../includes/session-config.php';
header('Content-Type: application/json');
require_once '../includes/dbh.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['emp_id'])) {
    $taskId = $_POST['task_id'] ?? '';
    $status = $_POST['status'] ?? '';
    
    // Update the task status in the database
    $stmt = $con->prepare("UPDATE tdl SET status = ? WHERE tdl_id = ? AND emp_id = ?");
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
