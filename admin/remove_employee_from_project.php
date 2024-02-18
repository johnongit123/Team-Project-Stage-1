<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['emp_id']) && isset($_GET['project_ids'])) {
    $employee_id = $_GET['emp_id'];
    $project_ids = isset($_GET['project_ids']) ? explode(',', $_GET['project_ids']) : [];
    try {
        require_once '../includes/dbh.php';
        $errors = [];
        
        if (empty($employee_id) || empty($project_ids) || (count($project_ids) === 1 && empty($project_ids[0]))) {
            $errors["empty_input"] = "Employee is not assigned to a project.";
        }
        
        if (!empty($errors)){
            $_SESSION["errors_project_remove_assign"] = $errors;
            header("Location: project.php");
            die();
        } else {
            foreach ($project_ids as $project_id) {
                remove_member($project_id, $employee_id);
            }
            // Redirect back to the page where the employee list is displayed
            $_SESSION["success_remove"] = "Employee has been removed from the project(s)!";
            header("Location: project.php");
            die(); 
        }
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function success_remove(){
    if (isset($_SESSION["success_remove"])){
        $success_message= $_SESSION["success_remove"];
        unset($_SESSION["success_remove"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_remove_errors(){
    if (isset($_SESSION["errors_project_remove_assign"])){
        $errors = $_SESSION["errors_project_remove_assign"];
        unset($_SESSION["errors_project_remove_assign"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}


// Function to remove member from project
function remove_member($project_id , $employee_id){
    global $con;

    $query = "UPDATE assigns SET project_id = NULL WHERE project_id IS NOT NULL AND emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    $job_role = retrieve_job_role($employee_id);

    if ($job_role == 'team_leader' || $job_role == 'admin'){
        $query_JR = "UPDATE project SET manager_name = NULL WHERE project_id = ?";
        $stmt_JR = $con->prepare($query_JR);
        $stmt_JR->bind_param("i", $project_id);
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
