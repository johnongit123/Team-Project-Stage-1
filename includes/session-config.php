<?php

session_set_cookie_params([
    'lifetime' => 1800,
    'path' => '/',
    'secure' => true
]);

session_start();

function check_login(){
    if (!isset($_SESSION["emp_id"])){
        header("Location: ../login.php");
        exit();
    } else {
        $emp_id = $_SESSION['emp_id'];
        return $emp_id;
    }
}
