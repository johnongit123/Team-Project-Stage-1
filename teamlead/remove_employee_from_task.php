<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['emp_id']) && isset($_GET['task_ids'])) {
    $employee_id = $_GET['emp_id'];
    $task_ids = isset($_GET['task_ids']) ? explode(',', $_GET['task_ids']) : [];
    try {
        require_once '../includes/dbh.php';
        $errors = [];
        
        if (empty($employee_id) || empty($task_ids) || (count($task_ids) === 1 && empty($task_ids[0]))) {
            $errors["empty_input"] = "Employee is not assigned to a task.";
        }
        
        if (!empty($errors)){
            $_SESSION["errors_task_remove"] = $errors;
            header("Location: tasks.php");
            die();
        } else {
            foreach ($task_ids as $task_id) {
                remove_member($task_id, $employee_id);
            }
            // Redirect back to the page where the employee list is displayed
            $_SESSION["success_remove_task"] = "Employee has been removed from the task(s)!";
            header("Location: tasks.php");
            die(); 
        }
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function success_remove_task(){
    if (isset($_SESSION["success_remove_task"])){
        $success_message= $_SESSION["success_remove_task"];
        unset($_SESSION["success_remove_task"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_remove_errors_task(){
    if (isset($_SESSION["errors_task_remove"])){
        $errors = $_SESSION["errors_task_remove"];
        unset($_SESSION["errors_task_remove"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}


// Function to remove member from project
function remove_member($task_id , $employee_id){
    global $con;

    $query = "UPDATE assigns SET task_id = NULL WHERE task_id IS NOT NULL AND emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    $job_role = retrieve_job_role($employee_id);

    if ($job_role == 'team_leader' || $job_role == 'admin'){
        $query_JR = "UPDATE task SET manager_name = NULL WHERE task_id = ?";
        $stmt_JR = $con->prepare($query_JR);
        $stmt_JR->bind_param("i", $task_id);
        $stmt_JR->execute();
        $stmt_JR->close();
    }  
}


function retrieve_job_role($employee_id){
    global $con;
    $query = "SELECT job_role FROM employee WHERE emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($job_role);
    $stmt->fetch();
    $stmt->close();
    return $job_role;
}
