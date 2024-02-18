<?php
session_start();
require_once 'includes/login-inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="css/log_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    
</head>
<body>

<div class="hero">
    <h1> Login </h1>
    <div class = "signup-box">
        <div class="left-box">
            <form action="includes/login-inc.php" method="post">
                <input type="email" id="email" name="email" placeholder="Enter Email" class="input-box" required>
                <input type="password" id="password" name="password" placeholder="Enter Password" class="input-box" required>
                <button id="loginBtn" type="submit">LOGIN<span>&#x27f6;</span></button>
            </form>
            <?php
                check_login_errors();
            ?>
        </div>
        <div class="right-box">
            <div class="logo">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
        </div>
    </div>
    </div>
<script src="https://kit.fontawesome.com/098b4711ab.js" crossorigin="anonymous"></script>  
</body>
</html>
