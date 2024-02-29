<?php
require_once '../includes/session-config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['emp_id'])) {
    $taskName = $_POST['task_name'] ?? '';
    $dueDate = $_POST['due_date'] ?? '';
    $empId = $_SESSION['emp_id'];
    $tdlID = rand(1000, 9999);

    try {
        require_once '../includes/dbh.php';
        $errors = [];

        if (strlen($taskName) > 100) {
            $errors['task_name'] = 'Tasks cannot be longer than 100 characters.';
        }
        
        if(empty($taskName) || empty($dueDate)){
            $errors["empty_input"] = "Please fill in all fields!";
        }

        if(check_tdtask_id($tdlID)){
            $errors["dupe"] = "Error creating task, please retry!";
        }
        // Get the emp_id from the session
        // Assuming `task_id` is auto-incremented in the database, you shouldn't need to set it manually
        // Also assuming that the 'status' column will be set to 'in progress' by default in the database
    
        
    
        if (!empty($errors)){
            $_SESSION["errors_tdtasks"] = $errors;
            header("Location: tasks.php");
            die();
        } else {
            add_td_task($tdlID, $taskName, $dueDate, $empId);
            $_SESSION["success_tdtasks"] = "Task has been added successfully!";
            header("Location: project.php");
            die();
        }

    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }


} 
   // echo json_encode(['status' => 'error', 'message' => 'No data provided or user not logged in']);


function success_tdtask(){
    if (isset($_SESSION["success_tdtasks"])){
        $success_message= $_SESSION["success_tdtasks"];
        unset($_SESSION["success_tdtasks"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_tdtask_errors(){
    if (isset($_SESSION["errors_tdtasks"])){
        $errors = $_SESSION["errors_tdtasks"];
        unset($_SESSION["errors_tdtasks"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}

function check_tdtask_id($tdlID) {
    global $con;
    $query = "Select tdl_id From tdl WHERE tdl_id = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $tdlID);
    $stmt->execute();

    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    
    return $result;
}



function add_td_task($tdlID, $taskName, $dueDate, $empId){
    global $con;
    $startStatus = "In Progress";
    $query = "INSERT INTO tdl (tdl_id, tdl_name, due_date, emp_id, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("issis", $tdlID, $taskName, $dueDate, $empId, $startStatus);
    $stmt->execute();
    $stmt->close();
}
