<?php
require_once '../includes/session-config.php';


// Check if employee ID is provided in the URL
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = $_POST['emp_id'];
    $project_id = $_POST['project_id'];

    try {
        require_once '../includes/dbh.php';
        //Error handling
        $errors = [];
        if (get_teamleader_count($employee_id) > 1){
            $errors[] = "Project leaders can only be assigned to one project.";
        }

        if (get_admin_count($employee_id) > 1){
            $errors[] = "Project leaders can only be assigned to one project.";
        }

        if(get_employee_count($employee_id) >= 2){
            $errors[] = "Employee cannot be assigned to more than 2 projects.";
        }

        if(assignment_count($employee_id, $project_id) > 0){
            $errors[] = "Employee is already assigned to the selected project.";
        }


        if (!empty($errors)){
            $_SESSION["errors_project_assign"] = $errors;
            header("Location: project.php");
            die();
        } else {
            assign_member($project_id, $employee_id);
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

function assign_member($project_id, $employee_id){
    global $con;
    // Prepare the SQL statement
    $query = "INSERT INTO assigns (project_id, emp_id) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $project_id, $employee_id);
    $stmt->execute();
    $stmt->close();
}


function get_teamleader_count($employee_id){
    global $con;
    $query = "SELECT COUNT(*) AS leader_count FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id WHERE e.job_role = 'team_leader' AND a.project_id IS NOT NULL AND a.emp_id = ?";
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