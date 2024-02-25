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

        if (empty($employee_id) || empty($project_id)){
            $errors["empty"] = "No inputs found";
        }

        //checks if theres already a team leader or admin (both project leaders) already assigned
        if (($job_role == 'team_leader' || $job_role == 'admin') && is_admin_or_team_leader_assigned($project_id)>0) {
            $errors["max_projectlead"] = "A Project leader is already assigned to the selected project.";
        }


        if (is_admin_or_team_leader_available($employee_id) > 0){
            $errors["projectlead_assign"] = "Project leaders can only be assigned to one project.";
        }

        if(assignment_count($employee_id, $project_id) > 0){
            $errors["employee_assigned"] = "Employee is already assigned to the selected project(s).";
        }

        if(is_employee_available($employee_id, $project_id) >= 1){
            $errors["max_employee"] = "Employee cannot be assigned to more than 1 project.";
        }

        if (!empty($errors)){
            $_SESSION["errors_project_assign"] = $errors;
            header("Location: project.php");
            die();
        } else {
            if($job_role == 'team_leader' || $job_role == 'admin'){
                assign_manager($project_id, $employee_id, $staff_name);
            } else {
                assign_member($project_id, $employee_id);
            }

            $_SESSION["success_assign"] = "Employee has been added to the project!"; // Redirect back to the page where the employee list is displayed
            header("Location: project.php");
            die();    
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

function assign_manager($project_id, $employee_id, $staff_name){
    global $con;

    $existing_count = get_employee_count($employee_id);
    if ($existing_count == 0) {
        $query = "INSERT INTO assigns (project_id, emp_id) VALUES (?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $project_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    } else{
        $query = "UPDATE assigns SET project_id = ? WHERE emp_id = ? AND project_id IS NULL LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $project_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    }
    
    $query_JR = "UPDATE project SET manager_name = ? WHERE project_id = ?";
    $stmt_JR = $con->prepare($query_JR);
    $stmt_JR->bind_param("si", $staff_name, $project_id);
    $stmt_JR->execute();
    $stmt_JR->close();
}

function assign_member($project_id, $employee_id){
    global $con;
    $existing_count = get_employee_count($employee_id);
    if ($existing_count < 1) {
        $query = "INSERT INTO assigns (project_id, emp_id) VALUES (?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $project_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // If the employee has two entries, find and update the entry with project_id as null
        $query = "UPDATE assigns SET project_id = ? WHERE emp_id = ? AND project_id IS NULL LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $project_id, $employee_id);
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



function is_admin_or_team_leader_assigned($project_id) {
    global $con;
    $query = "SELECT COUNT(*) FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id 
    WHERE (e.job_role = 'admin' OR e.job_role = 'team_leader') AND a.project_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count;
}

function is_admin_or_team_leader_available($employee_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id 
    WHERE (e.job_role = 'admin' OR e.job_role = 'team_leader') AND a.project_id IS NOT NULL AND a.emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($leader_count);
    $stmt->fetch();
    $stmt->close();

    return $leader_count;
}

function is_employee_available($employee_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns AS a INNER JOIN employee AS e ON a.emp_id = e.emp_id 
    WHERE (e.job_role = 'employee') AND a.project_id IS NOT NULL AND a.emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($leader_count);
    $stmt->fetch();
    $stmt->close();

    return $leader_count;
}
