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
            $errors["login_incorrect"] = "Incorrect email or password, please try again!(email related)";
        } 

        //checks if the email is valid and the password is correct
        if (!is_email_wrong($email) && is_password_wrong($pwd, $email)) {
            $errors["login_incorrect"] = "Incorrect email or password, please try again! (password related)";
        }

        $loginId = collect_login_id($email);
        
        if($loginId !== null){
            regen_session_id_loggedin($loginId);  
        } else{
            $errors["login_incorrect"] = "There has been an issue with the user session, please contact IT Services!";
        }

        

        $login_id = validate_login($email, $pwd);
        if ($login_id) {
            $_SESSION['login_id'] = $login_id;
            connect_user($email);
            die();
        } else{
            $errors["login_incorrect"] = "There has been an issue with the user session, please contact IT Services!";
        }


        //assigns error message to the session
        if (!empty($errors)){
            $_SESSION["errors_login"] = $errors;
            header("Location: ../login.php");
            die();
        }
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