<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $project_name = $_POST['project_name'];
    $start_date_str = $_POST['start_date'];
    $end_date_str = $_POST['end_date'];
    $status= $_POST['status'];
    $description = $_POST['description'];
    $selected_manager = $_POST['selected_manager'];
    $selected_employees = explode(",", $_POST['selected_employees']);
    

    try {
        require_once '../includes/dbh.php';
        $errors = [];

        if (strlen($project_name) > 29) {
            $errors['project_name'] = 'Project name must be less than 30 characters.';
        }
        if(empty($description) || empty($selected_manager) || empty($selected_employees)){
            $errors["empty_input"] = "Please fill in all fields!";
        }

        if (str_word_count($description) > 100) {
            $errors['description'] = 'Project description cannot exceed 100 words.';
        }

        if (does_project_exist($project_name, $project_id)){
            $errors["project_exists"] = "This project already exists!";
        }

        if ($start_date > $end_date) {
            $errors['date_range'] = 'Start date cannot be after end date.';
        }
        
        //assigns error message to the session
        if (!empty($errors)){
            $_SESSION["errors_project"] = $errors;
            header("Location: project.php");
            die();
        } else {
            $project_id = rand(1000, 9999);

            $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $start_date_str)));
            $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $end_date_str)));

            $manager_names = explode(" ", $selected_manager);
            $manager_first_name = $manager_names[0];
            $manager_last_name = $manager_names[1];
                    
            $selected_manager_id = get_emp_id_by_manager_name($manager_first_name, $manager_last_name);
            
            $employee_emp_ids = [];

            foreach ($selected_employees as $employee_name) {
                $emp_id = get_emp_id_by_employee_name($employee_name);
                if ($emp_id) {
                    $employee_emp_ids[] = $emp_id;
                }
            }
            
            
            create_project($project_id, $project_name, $description, $start_date, 
            $end_date, $status, $selected_manager_id, $selected_employees); // add to project database

            $_SESSION["success"] = "Project has been created successfully!";
            header("Location: project.php");
            die();
        }
        die();
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function success_message(){
    if (isset($_SESSION["success"])){
        $success_message= $_SESSION["success"];
        unset($_SESSION["success"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_project_errors(){
    if (isset($_SESSION["errors_project"])){
        $errors = $_SESSION["errors_project"];
        unset($_SESSION["errors_project"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}


function create_project($project_id, $project_name, $description, 
$start_date, $end_date, $status, $selected_manager_id, $selected_employees){
    global $con;

    $query = "INSERT INTO project (project_id, project_name, description, start_date, end_date, status) 
    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($query);
    $stmt->bind_param("isssss", $project_id, $project_name, $description, $start_date, $end_date, $status);
    $result = $stmt->execute();
    $project_id = $stmt->insert_id;

    assign_to_project($selected_manager_id, $project_id);

    foreach ($selected_employees as $emp_id) {
        assign_to_project($emp_id, $project_id);
    }

    return $result;
}



function does_project_exist($project_name, $project_id){
    $check1 = check_db_project_name($project_name);
    $check2 =  get_project_id($project_id);

    if ($check1 != null || $check2 != null){
        return true;
    } else {
        return false;
    }
}

function check_db_project_name($project_name){
    global $con;

    $query = "Select project_name From project WHERE project_name = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $project_name);
    $stmt->execute();

    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    
    return $result;
}

function get_project_id($project_id) {
    global $con;
    $query = "Select project_id From project WHERE project_id = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();

    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    
    return $result;
}



//Adding employee's to
function get_employee_id($employeeName){
    global $con;

    $query = "SELECT emp_id FROM employee WHERE CONCAT(first_name, ' ', last_name) = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $employeeName);
    $stmt->execute();
    $stmt->bind_result($emp_id);
    $stmt->fetch();
    $stmt->close();

    return $emp_id;
}

function assign_to_project($emp_id, $project_id)
{
    global $con;

    $query = "INSERT INTO assigns (emp_id, project_id) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $emp_id, $project_id);
    $stmt->execute();
    $stmt->close();
}


function get_emp_id_by_manager_name($manager_first_name, $manager_last_name) {
    global $con;

    $query = "SELECT emp_id FROM employee WHERE first_name = ? AND last_name = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $manager_first_name, $manager_last_name);
    $stmt->execute();
    $stmt->bind_result($emp_id);
    $stmt->fetch();
    $stmt->close();

    return $emp_id;
}

function get_emp_id_by_employee_name($employee_name) {
    global $con;

    $query = "SELECT emp_id FROM employee WHERE CONCAT(first_name, ' ', last_name) = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $employee_name);
    $stmt->execute();
    $stmt->bind_result($emp_id);
    $stmt->fetch();
    $stmt->close();

    return $emp_id;
}