<?php
session_start();
include('./index.php'); // Include your database connection script

header('Content-Type: application/json');

// Check if the user is logged in
if (isset($_SESSION['emp_id'])) {
    $empId = $_SESSION['emp_id'];

    // Prepare the query to select tasks
    $stmt = $conn->prepare("SELECT * FROM to_do_list WHERE emp_id = ? ORDER BY task_id DESC");
    $stmt->bind_param("i", $empId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all tasks
    $tasks = $result->fetch_all(MYSQLI_ASSOC);

    // Send back the tasks as JSON
    echo json_encode(['status' => 'success', 'tasks' => $tasks]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
}
?>
