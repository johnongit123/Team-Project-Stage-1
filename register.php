<?php
require_once 'includes/session-config.php';
require_once 'includes/dbh.php';
require_once 'includes/register-inc.php';

if(isset($_GET['invitecode'])){
    $inviteCode = $_GET['invitecode'];
    if(empty($inviteCode)) {
        echo "Error: Unauthorised Access ";
        die();
    }
} else {
    echo "Error: Unauthorised Access ";
    die();
}


?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="css/reg_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    
</head>
<body>

<div class="hero">
    <h1> Register </h1>
    <div class = "signup-box">
        <div class="left-box">
            <form action="includes/register-inc.php" method="post">
                <input type="text" id="first_name" name="first_name" placeholder="Enter Your First Name"  class="input-box" required >
                <input type="text" id="last_name" name="last_name" placeholder="Enter Your Last Name"  class="input-box" required>
                <input type="email" id="email" name="email" placeholder="Enter Staff Email"  class="input-box" required>
                <input type="password" id="password" name="password" placeholder="Create Password"  class="input-box" required>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password"  class="input-box" required>
                <input type="checkbox" id="terms" required>
                <label for="terms">I accept the terms and conditions</label>
                <button id="registerBtn" type="submit">SIGN UP <span>&#x27f6;</span></button>
            </form>
            <?php
                check_registration_errors();
            ?>
        </div>
        <div class="right-box">
            <div class="logo">
                <i class="fa-solid fa-screwdriver-wrench"></i></a>
            </div>
        </div>
    </div>
    <p class="login-msg">Already have an account? <a href="login.php">Login Here </a></p>
</div>
<script src="https://kit.fontawesome.com/098b4711ab.js" crossorigin="anonymous"></script>  
</body>
</html>