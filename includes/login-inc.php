<?php
require_once 'session-config.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // receiving inputs
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    

    try {
        require_once 'dbh.php';
        require_once 'log-query.php';
        require_once 'log-func.php';


        $errors = [];
        // looks for empty inputs fields   
        if (is_input_empty($email, $pwd)) {
            $errors["empty_input"] = "Please fill in all fields!";
        } 

        //checks if the email is valid
        if (is_email_wrong($email)) {
            $errors["login_incorrect"] = "Incorrect email or password, please try again!";
        } 

        //checks if the email is valid and the password is correct
        if (!is_email_wrong($email) && is_password_wrong($pwd, $email)) {
            $errors["login_incorrect"] = "Incorrect email or password, please try again!";
        }

        //assigns error message to the session
        if (!empty($errors)){
            $_SESSION["errors_login"] = $errors;
            header("Location: ../login.php");
            die();
        }
      
        
        $emp_id = get_empid($email);
        $_SESSION['emp_id'] = $emp_id;
        connect_user($email);
        die();    
    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function check_login_errors(){
    if (isset($_SESSION["errors_login"])){
        $errors = $_SESSION["errors_login"];
        unset($_SESSION["errors_login"]);
        echo "<br>";
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}
