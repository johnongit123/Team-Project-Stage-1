<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $project_name = $_POST['project_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status= $_POST['status'];
    $project_id = rand(1000, 9999);

    try {
        require_once '../includes/dbh.php';

        $errors = [];
        if (!did_project_create($project_id, $project_name, $start_date, $end_date)){
            $errors["invalid_creation"] = "There was an error making the project!";
        }

        if (does_project_exist($project_name, $start_date)){
            $errors["project_exists"] = "The project already exists!";
        }
        
        //assigns error message to the session
        if (!empty($errors)){
            $_SESSION["errors_project"] = $errors;
            header("Location: ../project.php");
            die();
        }
        
        
        $result = create_project($project_id, $project_name, $start_date, $end_date);
        if ($result === false) {
            throw new Exception("Error creating project.");
        }
        

        header("Location: ../project.php?success=1");
        exit();
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function success_message(){
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<p class="form-success">Project has been created successfully!</p>';
    }
}

function check_project_errors(){
    if (isset($_SESSION["errors_project"])){
        $errors = $_SESSION["errors_project"];
        unset($_SESSION["errors_project"]);
        echo "<br>";
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    } else{
        echo ' <P class="form-success"> Project has succesfully been made! </p>';
    }
}


function create_project($project_id, $project_name, $start_date, $end_date, $status){
    global $con;

    $query = "INSERT INTO project (project_id, project_name, start_date, end_date, status) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $con->prepare($query);
    $stmt->bind_param("issss", $project_id, $project_name, $start_date, $end_date, $status);
    $result = $stmt->execute();

    return $result;
}

function did_project_create($project_id, $project_name, $start_date, $end_date, $status){
    if(create_project($project_id, $project_name, $start_date, $end_date, $status)){
        return true;
    } else{
        return false;
    }
}


function does_project_exist($project_name, $start_date){
    $result = check_db_project($project_name, $start_date);
    if (!empty($result)){
        return true;
    } else {
        return false;
    }
}

function check_db_project($project_name, $start_date){
    global $con;

    $query = "Select project_name From project WHERE project_name = ? AND start_date = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $project_name, $start_date);
    $stmt->execute();

    $result = $stmt->num_rows > 0;
    $stmt->close();
    
    return $result;
}
