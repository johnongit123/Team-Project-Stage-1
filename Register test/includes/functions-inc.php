<?php

function validate_reg($pdo, $first_n, $last_n, $email, $pwd, $c_pwd){
    //error handler
    $errors = [];
    // looks for empty inputs fields   
    if (is_input_empty($first_n, $last_n, $email, $pwd, $c_pwd)) {
        $errors["empty_input"] = "Please fill in all fields!";
    } 
    
    //checks if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["invalid_email"] = "Invalid email format!";
    }

    //checks whether email is already in the database
    if (!is_email_valid($pdo, $email)){
        $errors["email_exists"] = "Invalid email used!";
    }

    //checks database to see if user has already been registered or not
    if  (is_reg_available($pdo, $email)){
        $errors["invalid_register"] = "You have already registered!";
    }

    //strong password test
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[\w]{6,}$/", $pwd)) {
        $errors["invalid_password"] = "Password must contain at least one lowercase letter, one uppercase letter, one number, and be at least 6 characters long.";
    }
    if ($pwd != $c_pwd) {
        $errors["invalid_confirm_password"] = "Password did not match.";
    }
    if (is_job_role_valid($pdo, $email)) {
        $errors["invalid_job_role"] = "There is a server-side issue, please contact IT services as soon as possible";
    }

    return $errors;
}



// error-detection section
// check empty input fields
function is_input_empty(string $firstN, string $lastN, string $email, string $pwd) {
    if(empty($firstN) || empty($lastN) || empty($email)|| empty($pwd)) {
        return true;
    } else {
        return false;
    }
}



//Valid email (does it match database)
function is_email_valid(object $pdo, string $email) {
    if(get_email($pdo, $email)) {
        return true;
    } else {
        return false;
    }
}


function get_email(object $pdo, string $email){
    $query = "SELECT email FROM employee 
    WHERE email = :email";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

//checks registration column in database 
//to see if users are(Y) or are not(N) already registered
function is_reg_available(object $pdo, string $email) {
    $result = check_registration_status($pdo, $email);
    if ($result && $result['is_registered'] == "Y") {
        return true;
    } else {
        return false;
    }
}

function check_registration_status(object $pdo, string $email) {
    // Prepare the SQL query with a parameter placeholder
    $query = "SELECT is_reg FROM employee 
    WHERE email = :email";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }



function is_job_role_valid(object $pdo, string $email) {
    $result = check_job_role($pdo, $email);
    if ($result && !in_array($result['job_role'], ["admin", "employee", "task_lead"])) {
        return true;
    } else {
        return false;
    }
}

function check_job_role(object $pdo, string $email) {
    // Prepare the SQL query with a parameter placeholder
    $query = "SELECT job_role FROM employee 
    WHERE email = ?";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam("s", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }


function create_user(object $pdo, string $firstN, string $lastN, string $email, string $pwd){
    $pwd_h= password_hash($pwd, PASSWORD_DEFAULT); //hash password for security
    
    $update_query = "UPDATE employee SET password = ? , is_reg = 'Y'
     WHERE first_name = ? AND last_name = ? AND email = ?"; //sql statemnt

    //preparing, binding and executing statement
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bind_param("ssss",$pwd_h, $firstN, $lastN, $email);
    $update_stmt->execute();

    // Query to retrieve the job role of the user
    $role_query = "SELECT job_role FROM employee WHERE email = ?";
    $role_stmt = $pdo->prepare($role_query);
    $role_stmt->bindParam("s", $email);
    $role_stmt->execute();

    // Fetch the job role
    $role_result = $role_stmt->fetch(PDO::FETCH_ASSOC);

    // Redirect the user based on their job role
    if ($role_result && isset($role_result['job_role'])) {
        $job_role = $role_result['job_role'];
        switch ($job_role) {
            case 'admin':
                header("Location: ../a_dash.html");
                break;
            case 'task_lead':
                header("Location: ../faq.html"); //TEMPORARY
                break;
            case 'employee':
                header("Location: ../s_dash.html");
                break;
            default:
                // Default redirect if job role is not recognized
                header("Location: ../register.php");
                break;
        }
    } else {
        // Redirect if job role is not found
        header("Location: ../register.php");
    }
}

