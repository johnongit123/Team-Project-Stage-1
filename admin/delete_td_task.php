<?php
require_once '../includes/session-config.php';
header('Content-Type: application/json');
require_once '../includes/dbh.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tdl_id'])) {
    $tdTaskId = $_POST['tdl_id'];
    
    $stmt = $con->prepare("DELETE FROM tdl WHERE tdl_id = ?");
    $stmt->bind_param("i", $tdTaskId);
    
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
