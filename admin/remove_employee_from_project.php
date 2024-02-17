<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['emp_id']) && isset($_GET['project_ids'])) {
    $employee_id = $_GET['emp_id'];
    $project_ids = explode(',', $_GET['project_ids']);
    try {
        require_once '../includes/dbh.php';
        $errors = [];
        
        if (empty($project_ids)) {
            $errors["empty_input"] = "Employee is not assigned to a project.";
        }
        
        if (!empty($errors)){
            $_SESSION["errors_project_remove_assign"] = $errors;
            header("Location: project.php");
            die();
        } else {
            foreach ($project_ids as $project_id) {
                if (count_assigned_employees($employee_id) > 1) { //if multiple projects are found with the user, delete the assignment
                    remove_member($project_id, $employee_id);
                } else{
                    set_project_id_null($project_id, $employee_id); //If only one employee is assigned, set the value to null
                }
            }
            // Redirect back to the page where the employee list is displayed
            $_SESSION["success_remove"] = "Employee has been removed from the project(s)!";

            header("Location: project.php");
            exit(); // Terminate script execution after redirect
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
function remove_member($project_id, $employee_id){
    global $con;

    $job_role = retrieve_job_role($employee_id);
    // Prepare the SQL statement
    $query = "DELETE FROM assigns WHERE project_id = ? AND emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $project_id, $employee_id);
    $stmt->execute();
    $stmt->close();

    if ($job_role == 'team_leader' || $job_role == 'admin'){
        $query_JR = "UPDATE project SET manager_name = NULL WHERE project_id = ?";
        $stmt_JR = $con->prepare($query_JR);
        $stmt_JR->bind_param("i", $project_id);
        $stmt_JR->execute();
        $stmt_JR->close();
    }
}

function set_project_id_null($project_id, $employee_id){
    global $con;

    $job_role = retrieve_job_role($employee_id);

    $query = "UPDATE assigns SET project_id = NULL WHERE project_id = ? AND emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $project_id, $employee_id);
    $stmt->execute();
    $stmt->close();


    if ($job_role == 'team_leader' || $job_role == 'admin'){
        $query_JR = "UPDATE project SET manager_name = NULL WHERE project_id = ?";
        $stmt_JR = $con->prepare($query_JR);
        $stmt_JR->bind_param("i", $project_id);
        $stmt_JR->execute();
        $stmt_JR->close();
    }
}


function count_assigned_employees($emp_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns WHERE emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count;
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
