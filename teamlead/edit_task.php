<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $end_date_str = $_POST['end_date'];
    $status= $_POST['status'];
    $priority = $_POST['priority'];

    try {
        require_once '../includes/dbh.php';
        $errors = [];


        if (empty($task_name) || empty($end_date_str) || empty($status) || empty($priority)) {
            $errors["empty_input"] = "Please fill in all fields!";
        }

        if (strlen($task_name) > 100) {
            $errors['task_name'] = 'Task name must be less than 100 characters.';
        }

        if (!empty($errors)) {
            $_SESSION["errors_update_task"] = $errors;
            header("Location: tasks.php");
            die();
        }
        update_task($task_id, $task_name, $end_date_str, $status, $priority);

        $_SESSION["success_update_task"] = "Task has been updated successfully!";
        header("Location: tasks.php");
        die();
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}


function success_edit_task(){
    if (isset($_SESSION["success_update_task"])){
        $success_message= $_SESSION["success_update_task"];
        unset($_SESSION["success_update_task"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_edit_task_errors(){
    if (isset($_SESSION["errors_update_task"])){
        $errors = $_SESSION["errors_update_task"];
        unset($_SESSION["errors_update_task"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}

// Function to update project in the database
function update_task($task_id, $task_name, $end_date_str, $status, $priority) {
    global $con;

    $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $end_date_str)));

    // Prepare and execute SQL update statement
    $query = "UPDATE task SET task_name = ?, end_date= ? , status = ?, priority = ? WHERE task_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssi", $task_name, $end_date, $status, $priority, $task_id);
    $stmt->execute();
    $stmt->close();
}
?>
