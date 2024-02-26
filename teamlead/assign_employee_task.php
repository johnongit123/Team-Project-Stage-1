<?php
require_once '../includes/session-config.php';


// Check if employee ID is provided in the URL
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = $_POST['emp_id'];
    $task_id = $_POST['task_id'];
    $job_role = $_POST['job_role'];
    $staff_name = $_POST['staff_name'];

    try {
        require_once '../includes/dbh.php';
        //Error handling
        $errors = [];

        if (empty($employee_id) || empty($task_id)){
            $errors["empty"] = "No inputs found";
        }

        //checks if theres already a team leader or admin (both project leaders) already assigned
        if (($job_role == 'team_leader' || $job_role == 'admin') && is_admin_or_team_leader_assigned($task_id)>0) {
            $errors["max_projectlead"] = "A Project leader is already assigned to the selected task.";
        }

        if(assignment_count($employee_id, $task_id) > 0){
            $errors["employee_assigned"] = "Employee is already assigned to the selected task(s).";
        }

        if(is_employee_available($employee_id, $task_id) >= 1){
            $errors["max_employee"] = "Employee cannot be assigned to more than 1 task.";
        }

        if (!empty($errors)){
            $_SESSION["errors_task_assign"] = $errors;
            header("Location: tasks.php");
            die();
        } else {
            if($job_role == 'team_leader'){
                assign_manager($task_id, $employee_id, $staff_name);
            } else {
                assign_member($task_id, $employee_id);
            }

            $_SESSION["success_assign_task"] = "Employee has been added to the task!"; // Redirect back to the page where the employee list is displayed
            header("Location: tasks.php");
            die();    
        }
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}


function success_assign_task(){
    if (isset($_SESSION["success_assign_task"])){
        $success_message= $_SESSION["success_assign_task"];
        unset($_SESSION["success_assign_task"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_assign_errors_task(){
    if (isset($_SESSION["errors_task_assign"])){
        $errors = $_SESSION["errors_task_assign"];
        unset($_SESSION["errors_task_assign"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}

function assign_manager($task_id, $employee_id, $staff_name){
    global $con;

    $existing_count = get_employee_count($employee_id);
    if ($existing_count == 0) {
        $query = "INSERT INTO assigns (task_id, emp_id) VALUES (?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $task_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    } else{
        $query = "UPDATE assigns SET task_id = ? WHERE emp_id = ? AND task_id IS NULL LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $task_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    }
    
    $query_JR = "UPDATE task SET manager_name = ? WHERE task_id = ?";
    $stmt_JR = $con->prepare($query_JR);
    $stmt_JR->bind_param("si", $staff_name, $task_id);
    $stmt_JR->execute();
    $stmt_JR->close();
}

function assign_member($task_id, $employee_id){
    global $con;
    $existing_count = get_employee_count($employee_id);
    if ($existing_count == 0) {
        $query = "INSERT INTO assigns (task_id, emp_id) VALUES (?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $task_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // If the employee has an entry, find and update the entry with task_id as null
        $query = "UPDATE assigns SET task_id = ? WHERE emp_id = ? AND task_id IS NULL LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $task_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    }
}




function get_employee_count($employee_id){
    global $con;
    $query = "SELECT COUNT(*) AS employee_count FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id WHERE e.job_role = 'employee' AND a.emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($employee_count);
    $stmt->fetch();
    $stmt->close();

    return $employee_count;
}

function assignment_count($employee_id, $task_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns WHERE emp_id = ? AND task_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $employee_id, $task_id);
    $stmt->execute();
    $stmt->bind_result($assignment_count);
    $stmt->fetch();
    $stmt->close();

    return $assignment_count;
}



function is_admin_or_team_leader_assigned($task_id) {
    global $con;
    $query = "SELECT COUNT(*) FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id 
    WHERE (e.job_role = 'admin' OR e.job_role = 'team_leader') AND a.task_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count;
}

function is_employee_available($employee_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id 
    WHERE (e.job_role = 'employee') AND a.task_id IS NOT NULL AND a.emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($leader_count);
    $stmt->fetch();
    $stmt->close();

    return $leader_count;
}
