<?php
require_once 'session-config.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_n = $_POST['first_name'];
    $last_n = $_POST['last_name'];
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $c_pwd = $_POST['confirm_password'];
    
    try {
        require_once 'dbh.php';
        require_once 'reg-query.php';
        require_once 'reg-func.php';

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
        if(is_user_email_invalid($first_n, $last_n, $email)){
            $errors["invalid_user_email"] = "Incorrect or Invalid email used!";
        }
    
        //checks database to see if user has already been registered or not
        if  (is_reg_unavailable($first_n, $last_n, $email)){
            $errors["invalid_register"] = "You have already registered!";
        }

        //checks database to see if user's full name exists in the database or not
        if  (is_name_invalid($first_n, $last_n)){
            $errors["invalid_user"] = "You are not authorised to make an account!";
        }

        //strong password test
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[\w]{6,}$/", $pwd)) {
            $errors["invalid_password"] = "Password must contain at least one lowercase letter, one uppercase letter, one number, and be at least 6 characters long.";
        }
        if ($pwd != $c_pwd) {
            $errors["invalid_confirm_password"] = "Password did not match.";
        }

        //assigns error message to the session
        if (!empty($errors)){
            $_SESSION["errors_register"] = $errors;
            header("Location: ../register.php");
            die();
        }
        
        $login_id = random_num(20);
        create_user($login_id, $first_n, $last_n, $email, $pwd);
        $_SESSION["login_id"] = $login_id;
        
        die();
    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}



// takes the error message in the session and sends to the user
function check_registration_errors(){
    if (isset($_SESSION["errors_register"])){
        $errors = $_SESSION["errors_register"];
        unset($_SESSION["errors_register"]);
        echo "<br>";
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}

//generate login id for session
function random_num($length){
    $text = "";
    if($length < 5){
        $length = 5;
    }

    $len = rand(4,$length);

    for ($i=0; $i < $len; $i++) { 
        $text .= rand(0,9);
    }

    return $text;
}