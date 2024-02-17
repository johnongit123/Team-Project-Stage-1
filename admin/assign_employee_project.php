<?php
require_once '../includes/session-config.php';


// Check if employee ID is provided in the URL
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = $_POST['emp_id'];
    $project_id = $_POST['project_id'];
    $job_role = $_POST['job_role'];
    $staff_name = $_POST['staff_name'];

    try {
        require_once '../includes/dbh.php';
        //Error handling
        $errors = [];


        if (get_teamleader_count($employee_id) > 0){
            $errors["teamleader_assigned"] = "Project leaders can only be assigned to one project.";
        }

        if (get_admin_count($employee_id) > 0){
            $errors["admin_assigned"] = "Project leaders (Admins) can only be assigned to one project.";
        }

        if ($job_role == 'team_leader' && get_teamleader_count_project($project_id) > 0){
            $errors["max_teamleader"] = "A Project leader is already assigned to the selected project.";
        }

        if ($job_role == 'admin' && get_admin_count_project($project_id) > 0){
            $errors["max_admin"] = "A Project leader (Admin) is already assigned to the selected project.";
        }


        if(get_employee_count($employee_id) >= 2){
            $errors["max_employee"] = "Employee cannot be assigned to more than 2 projects.";
        }

        if(assignment_count($employee_id, $project_id) > 0){
            $errors["employee_assigned"] = "Employee is already assigned to the selected project(s).";
        }


        if (!empty($errors)){
            $_SESSION["errors_project_assign"] = $errors;
            header("Location: project.php");
            die();
        } else {
            assign_member($project_id, $employee_id, $staff_name);
            // Redirect back to the page where the employee list is displayed
            $_SESSION["success_assign"] = "Employee has been added to the project!";
            header("Location: project.php");
            exit();
        }
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}


function success_assign(){
    if (isset($_SESSION["success_assign"])){
        $success_message= $_SESSION["success_assign"];
        unset($_SESSION["success_assign"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_assign_errors(){
    if (isset($_SESSION["errors_project_assign"])){
        $errors = $_SESSION["errors_project_assign"];
        unset($_SESSION["errors_project_assign"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}

function assign_member($project_id, $employee_id, $staff_name){
    global $con;
    $job_role = get_job_role($employee_id);
    if ($job_role == 'team_leader' || $job_role == 'admin'){
        $query_JR = "UPDATE project SET manager_name = ? WHERE project_id = ?";
        $stmt_JR = $con->prepare($query_JR);
        $stmt_JR->bind_param("si", $staff_name, $project_id);
        $stmt_JR->execute();
        $stmt_JR->close();

        $query = "INSERT INTO assigns (project_id, emp_id) VALUES (?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $project_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    }

    // Prepare the SQL statement
    $query = "INSERT INTO assigns (project_id, emp_id) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $project_id, $employee_id);
    $stmt->execute();
    $stmt->close();
    
}


function get_teamleader_count($employee_id){
    global $con;
    $query = "SELECT COUNT(*) AS leader_count FROM assigns AS a 
    INNER JOIN employee AS e ON a.emp_id = e.emp_id 
    WHERE e.job_role = 'team_leader' AND a.project_id IS NOT NULL AND a.emp_id = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($leader_count);
    $stmt->fetch();
    $stmt->close();

    return $leader_count;
}

function get_admin_count($employee_id){
    global $con;
    $query = "SELECT COUNT(*) AS leader_count FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id WHERE e.job_role = 'admin' AND a.project_id IS NOT NULL AND a.emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($leader_count);
    $stmt->fetch();
    $stmt->close();

    return $leader_count;
}

function get_employee_count($employee_id){
    global $con;
    $query = "SELECT COUNT(*) AS employee_count FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id WHERE e.job_role = 'employee' AND a.project_id IS NOT NULL AND a.emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($employee_count);
    $stmt->fetch();
    $stmt->close();

    return $employee_count;
}

function assignment_count($employee_id, $project_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns WHERE emp_id = ? AND project_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $employee_id, $project_id);
    $stmt->execute();
    $stmt->bind_result($assignment_count);
    $stmt->fetch();
    $stmt->close();

    return $assignment_count;
}

function get_teamleader_count_project($project_id){
    global $con;
    $query = "SELECT COUNT(*) AS leader_count FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id WHERE e.job_role = 'team_leader' AND a.project_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $stmt->bind_result($leader_count);
    $stmt->fetch();
    $stmt->close();

    return $leader_count;
}

function get_admin_count_project($project_id){
    global $con;
    $query = "SELECT COUNT(*) AS leader_count FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id WHERE e.job_role = 'admin' AND a.project_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $stmt->bind_result($leader_count);
    $stmt->fetch();
    $stmt->close();

    return $leader_count;
}


function get_job_role($employee_id){
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