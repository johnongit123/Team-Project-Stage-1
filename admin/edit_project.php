<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $project_id = $_POST['project_id'];
    $project_name = $_POST['project_name'];
    $start_date_str = $_POST['start_date'];
    $end_date_str = $_POST['end_date'];
    $status= $_POST['status'];
    $description = $_POST['description'];

    try {
        require_once '../includes/dbh.php';
        $errors = [];


        if (empty($project_name) || empty($start_date_str) || empty($end_date_str) || empty($status) || empty($description)) {
            $errors["empty_input"] = "Please fill in all fields!";
        }

        if (strlen($project_name) > 29) {
            $errors['project_name'] = 'Project name must be less than 30 characters.';
        }

        if (str_word_count($description) > 100) {
            $errors['description'] = 'Project description cannot exceed 100 words.';
        }

        if ($start_date_str > $end_date_str) {
            $errors['date_range'] = 'Start date cannot be after end date.';
        }

        if (!empty($errors)) {
            $_SESSION["errors_update"] = $errors;
            header("Location: project.php");
            die();
        }
        update_project($project_id, $project_name, $description, $start_date_str, $end_date_str, $status);

        $_SESSION["success_update"] = "Project has been updated successfully!";
        header("Location: project.php");
        die();
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}


function success_edit(){
    if (isset($_SESSION["success_update"])){
        $success_message= $_SESSION["success_update"];
        unset($_SESSION["success_update"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_edit_errors(){
    if (isset($_SESSION["errors_update"])){
        $errors = $_SESSION["errors_update"];
        unset($_SESSION["errors_update"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}

// Function to update project in the database
function update_project($project_id, $project_name, $description, $start_date_str, $end_date_str, $status) {
    global $con;

    // Convert date strings to proper format
    $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $start_date_str)));
    $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $end_date_str)));

    // Prepare and execute SQL update statement
    $query = "UPDATE project SET project_name = ?, description = ?, start_date = ?, end_date= ? , status = ? WHERE project_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $project_name, $description, $start_date, $end_date, $status, $project_id);
    $stmt->execute();
    $stmt->close();
}
?>
