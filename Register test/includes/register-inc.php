<?php
require_once 'session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_n = $_POST['first_name'];
    $last_n = $_POST['last_name'];
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $c_pwd = $_POST['confirm_password'];
    
    try {
        
        require_once 'dbh-inc.php';
        require_once 'functions-inc.php';

        $errors = validate_reg($pdo, $first_n, $last_n, $email, $pwd, $c_pwd);

        if (!empty($errors)){
            $_SESSION["errors_register"] = $errors;
            header("Location: ../register.php");
            exit;
        }
        

        create_user($pdo, $firstN, $lastN, $email, $pwd);

    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../register.php");
    exit();
}

// sending error message to client-side
function check_registration_errors(){
    if (isset($_SESSION["errors_register"])){
        $errors = $_SESSION["errors_register"];
        echo "<br>";
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
        unset($_SESSION["errors_register"]);
    }
}