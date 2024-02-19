<?php

declare(strict_types=1);


//checks database for associated email to users specificakky
function get_useremail(string $first_n, string $last_n, string $email){
    global $con;

    $query = "SELECT email FROM employee WHERE first_name = ? AND last_name = ? AND email = ?";
    
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sss', $first_n, $last_n, $email);
    $stmt->execute();

    $stmt->bind_result($email);
    
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    
    return $result;
}


//checks database for to see if user is already registered
function get_registration_status(string $first_n, string $last_n, string $email) {
    global $con;

    $query = "SELECT is_reg FROM employee WHERE first_name = ? AND last_name = ? AND email = ?";

    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sss', $first_n, $last_n, $email);
    $stmt->execute();

    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    
    return $result;
}

//checks database for to see if user full name exists in the database
function get_full_name(string $first_n, string $last_n) {
    global $con;

    $query = "SELECT first_name, last_name FROM employee WHERE first_name = ? AND last_name = ?";

    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ss', $first_n, $last_n);
    $stmt->execute();

    $stmt->bind_result($first_name, $last_name);
    $full_names = [];
    while ($stmt->fetch()) {
        $full_names[] = $first_name . ' ' . $last_name;
    }

    $stmt->close();

    if (empty($full_names)) {
        return null; 
    }
    return $full_names;
}

//save/update database
function create_user(int $login_id, string $first_n, string $last_n, string $email, string $pwd){
    global $con;
    $pwd_h= password_hash($pwd, PASSWORD_DEFAULT); //hash password for security

    $update_query = "UPDATE employee SET login_id = ?, password = ? , is_reg = 'Y'
     WHERE first_name = ? AND last_name = ? AND email = ?"; //sql statemnt

    //preparing, binding and executing statement
    $update_stmt = $con->prepare($update_query);
    $update_stmt->bind_param("issss", $login_id, $pwd_h, $first_n, $last_n, $email);
    $update_result = $update_stmt->execute();

    // Check if the statement executed successfully
    if ($update_result === false) {
        die("Error in executing statement: " . $update_stmt->error);
    }

    // Insert into assigns table
    $emp_id_query = "SELECT emp_id FROM employee WHERE first_name = ? AND last_name = ? AND email = ?";
    $emp_id_stmt = $con->prepare($emp_id_query);
    $emp_id_stmt->bind_param("sss", $first_n, $last_n, $email);
    $emp_id_stmt->execute();

    // Fetch the emp_id
    $emp_id_stmt->bind_result($emp_id);
    $emp_id_stmt->fetch();
    $emp_id_stmt->close();

    // Insert emp_id into assigns table
    $assigns_query = "INSERT INTO assigns (emp_id) VALUES (?)";
    $assigns_stmt = $con->prepare($assigns_query);
    $assigns_stmt->bind_param("i", $emp_id);
    $assigns_result = $assigns_stmt->execute();

    // Check if the statement executed successfully
    if ($assigns_result === false) {
        die("Error in executing statement: " . $assigns_stmt->error);
    }

    success_redirect($email);

}




function success_redirect($email){
    //send to specific site based on job_role in database
    // Query to retrieve the job role of the user
    global $con;

    $role_query = "SELECT job_role FROM employee WHERE email = ?";
    $role_stmt = $con->prepare($role_query);
    
    $role_stmt->bind_param("s", $email);
    $role_stmt->execute();

    // Fetch the job role
    $role_stmt->bind_result($job_role);
    $role_stmt->fetch();
    $role_stmt->close();
    

    // Redirect the user based on their job role
    if ($job_role !== null) {
        switch ($job_role) {
            case 'admin':
                header("Location: ../admin/project.php");
                break;
            case 'team_leader':
                header("Location: ../teamlead/tasks.php");
                break;
            case 'employee':
                header("Location: ../emp/tasks.php");
                break;
            default:
                // Default redirect if job role is not recognized
                header("Location: ../404.html");
                break;
        }
    } else {
        // Redirect if job role is not found
        header("Location: ../404.html");
    }
}
