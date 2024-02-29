<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_name = $_POST['task_name'];
    $end_date_str = $_POST['end_date'];
    $status= $_POST['status'];
    $priority = $_POST['priority'];
    $project_id = $_POST['project_id'];
    $manager_id = $_POST['manager_id'];
    $employee_id = $_POST['employee_id'];
    
    $task_id = rand(100000, 999999);

    try {
        require_once '../includes/dbh.php';
        $errors = [];

        if (strlen($task_name) > 100) {
            $errors['project_name'] = 'Tasks cannot be longer than 100 characters.';
        }
        if(empty($task_name) || empty($priority) || empty($project_id) || empty($employee_id) || empty($manager_id) || empty($end_date_str) || empty($status)){
            $errors["empty_input"] = "Please fill in all fields!";
        }

        if (is_project_full($project_id) >= 5){
            $errors["max_tasks"] = "This Project already has the maximum number of tasks!";
        }

        if (does_task_exist($task_name, $task_id)){
            $errors["task_exists"] = "This Task already exists!";
        }

        if (check_task_assignment($employee_id) >= 1){
            $errors["on_task"] = "This Person is already on a Task!";
        }

        //assigns error message to the session
        if (!empty($errors)){
            $_SESSION["errors_tasks"] = $errors;
            header("Location: project.php");
            die();
        } else {
            $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $end_date_str)));

            $manager_name = get_manager_name_by_id($manager_id);
            
            create_task($task_id, $task_name, $end_date, $status, $priority, 
            $manager_name, $project_id, $manager_id, $employee_id); //Adds task to database

            $_SESSION["success_tasks"] = "Task has been created successfully!";
            header("Location: project.php");
            die();
        }
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function success_task(){
    if (isset($_SESSION["success_tasks"])){
        $success_message= $_SESSION["success_tasks"];
        unset($_SESSION["success_tasks"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_task_errors(){
    if (isset($_SESSION["errors_tasks"])){
        $errors = $_SESSION["errors_tasks"];
        unset($_SESSION["errors_tasks"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}


function create_task($task_id, $task_name, $end_date, $status, $priority, $manager_name, $project_id, $manager_id, $employee_id){
    global $con;

    $query = "INSERT INTO task (task_id, task_name, end_date, status, priority, manager_name, project_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($query);
    $stmt->bind_param("isssssi", $task_id, $task_name, $end_date, $status, $priority, $manager_name, $project_id);
    $stmt->execute();
    $project_id = $stmt->insert_id;
    $stmt->close();

    assign_to_task($manager_id, $project_id);
    assign_to_task($employee_id, $project_id);
}

function does_task_exist($task_name, $task_id){
    $check1 = check_task_name($task_name);
    $check2 =  check_task_id($task_id);

    if ($check1 != null || $check2 != null){
        return true;
    } else {
        return false;
    }
}

function check_task_name($task_name){
    global $con;

    $query = "Select task_name From task WHERE task_name = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $task_name);
    $stmt->execute();

    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    
    return $result;
}

function check_task_id($task_id) {
    global $con;
    $query = "Select task_id From task WHERE task_id = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    
    return $result;
}


function assign_to_task($selected_id, $task_id){
    global $con;

    $count = assign_count($selected_id);

    if($count >= 1){
        $query = "UPDATE assigns set task_id = ? WHERE task_id IS NULL AND emp_id = ? LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $task_id, $selected_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $query = "INSERT INTO assigns (emp_id, task_id) VALUES (?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $selected_id, $task_id);
        $stmt->execute();
        $stmt->close();
    }
    
}



function get_manager_name_by_id($manager_id) {
    global $con;

    $query = "SELECT CONCAT(first_name, ' ', last_name) AS manager_name FROM employee WHERE emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $manager_id);
    $stmt->execute();
    $stmt->bind_result($manager_name);
    $stmt->fetch();
    $stmt->close();

    return $manager_name;
}




function assign_count($selected_id){
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

function is_project_full($project_id){
    global $con;
    $query = "SELECT COUNT(project_id) FROM task WHERE project_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count;
}

function check_task_assignment($selected_id){
    global $con;
    $query = "SELECT COUNT(*) FROM assigns WHERE task_id is NOT null and emp_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count;    
}
