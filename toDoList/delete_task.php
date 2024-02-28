<?php
session_start();
header('Content-Type: application/json');

include('./index.php'); // Make sure this includes the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];
    
    $stmt = $conn->prepare("DELETE FROM to_do_list WHERE task_id = ?");
    $stmt->bind_param("i", $taskId);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete task', 'error' => $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Task ID not provided or user not logged in']);
}
?>
