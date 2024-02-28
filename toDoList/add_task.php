<?php
session_start();
header('Content-Type: application/json');

include('./index.php'); // Make sure this includes the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['emp_id'])) {
    $taskName = $_POST['task_name'] ?? '';
    $dueDate = $_POST['due_date'] ?? '';
    $empId = $_SESSION['emp_id']; // Get the emp_id from the session
    // Assuming `task_id` is auto-incremented in the database, you shouldn't need to set it manually
    // Also assuming that the 'status' column will be set to 'in progress' by default in the database
    $stmt = $conn->prepare("INSERT INTO to_do_list (task_name, due_date, emp_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $taskName, $dueDate, $empId);
    
    
    // Execute and respond
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Task added successfully']);
    } else {
        // You can use $stmt->error to get the error message from the database
        echo json_encode(['status' => 'error', 'message' => 'Failed to add task', 'error' => $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data provided or user not logged in']);
}

?>
