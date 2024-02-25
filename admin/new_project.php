<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $project_name = $_POST['project_name'];
    $start_date_str = $_POST['start_date'];
    $end_date_str = $_POST['end_date'];
    $status= $_POST['status'];
    $description = $_POST['description'];
    $selected_manager_fullname = $_POST['selected_manager'];
    $selected_employee_fullname = $_POST['selected_employees'];
    $project_id = rand(1000, 9999);

    $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $start_date_str)));
    $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $end_date_str)));

    $manager_names = explode(" ", $selected_manager_fullname);
    $manager_first_name = $manager_names[0];
    $manager_last_name = $manager_names[1];

    $employee_names = explode(" ", $selected_employee_fullname);
    $employee_first_name = $employee_names[0];
    $employee_last_name = $employee_names[1];
            
    $selected_manager_id = get_id_by_manager_name($manager_first_name, $manager_last_name);
    $selected_emp_id = get_id_by_employee_name($employee_first_name, $employee_last_name);

    try {
        require_once '../includes/dbh.php';
        $errors = [];

        if (strlen($project_name) > 29) {
            $errors['project_name'] = 'Project name must be less than 30 characters.';
        }
        if(empty($description) || empty($selected_manager_fullname) || empty($selected_employee_fullname) || empty($status) || empty($start_date_str) || empty($end_date_str)){
            $errors["empty_input"] = "Please fill in all fields!";
        }

        if (str_word_count($description) > 100) {
            $errors['description'] = 'Project description cannot exceed 100 words.';
        }

        if (does_project_exist($project_name, $project_id)){
            $errors["project_exists"] = "This project already exists!";
        }

        if (check_project_assignment($selected_emp_id) >= 1 || check_project_assignment($selected_manager_id) >= 1){
            $errors["on_project"] = "This person is already on a Project!";
        }

        if ($start_date_str > $end_date_str) {
            $errors['date_range'] = 'Start date cannot be after end date.';
        }
        
        //assigns error message to the session
        if (!empty($errors)){
            $_SESSION["errors_project"] = $errors;
            header("Location: project.php");
            die();
        } else {
            
            
            create_project($project_id, $project_name, $description, $start_date, 
            $end_date, $status, $selected_manager_id, $selected_emp_id, $selected_manager_fullname); // add to project database

            $_SESSION["success"] = "Project has been created successfully!";
            header("Location: project.php");
            die();
        }
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function success_project(){
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
$start_date, $end_date, $status, $selected_manager_id, $selected_emp_id, $selected_manager_fullname){
    global $con;

    $query = "INSERT INTO project (project_id, project_name, description, start_date, end_date, status, manager_name) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($query);
    $stmt->bind_param("issssss", $project_id, $project_name, $description, $start_date, $end_date, $status, $selected_manager_fullname);
    $stmt->execute();
    $project_id = $stmt->insert_id;
    $stmt->close();

    assign_to_project($selected_manager_id, $project_id);
    assign_to_project($selected_emp_id, $project_id);
}

function does_project_exist($project_name, $project_id){
    $check1 = verify_ProjectName($project_name);
    $check2 =  get_project_id($project_id);

    if ($check1 != null || $check2 != null){
        return true;
    } else {
        return false;
    }
}

function verify_ProjectName($project_name){
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


function assign_to_project($selected_id, $project_id){
    global $con;

    $count = assign_occurences($selected_id);

    if($count >= 1){
        $query = "UPDATE assigns set project_id = ? WHERE project_id IS NULL AND emp_id = ? LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $project_id, $selected_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $query = "INSERT INTO assigns (emp_id, project_id) VALUES (?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $selected_id, $project_id);
        $stmt->execute();
        $stmt->close();
    }
    
}


function get_id_by_manager_name($manager_first_name, $manager_last_name) {
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

function get_id_by_employee_name($employee_first_name, $employee_last_name) {
    global $con;

    $query = "SELECT emp_id FROM employee WHERE first_name = ? AND last_name = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $employee_first_name, $employee_last_name);
    $stmt->execute();
    $stmt->bind_result($emp_id);
    $stmt->fetch();
    $stmt->close();

    return $emp_id;
}

function assign_occurences($selected_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns WHERE emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count;
}

function check_project_assignment($selected_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns WHERE project_id is NOT null and emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count;    
}
