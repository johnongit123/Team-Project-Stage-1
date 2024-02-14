<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800,
    'path' => '/',
    'Secure' => true,
    'httponly' => true
]);

session_start();





if(isset($_SESSION["user_id"])){  
    if (!isset($_SESSION["last_regeneration"])) {
        regen_session_id_loggedin($userId);
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            regen_session_id_loggedin($userId);
        }
    }
} else {
    if (!isset($_SESSION["last_regeneration"])) {
        regen_session_id();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            regen_session_id();
        }
    }
}



function regen_session_id_loggedin($userId){
    session_regenerate_id(true);

    $userId = $_SESSION["user_id"];
    $newSessionId = session_create_id();
    $sessionId = $newSessionId . "_". $userId;
    session_id($sessionId);

    $_SESSION["last_regeneration"] = time();
}


function regen_session_id(){
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}

function is_user_logged_in() {
    return isset($_SESSION["user_id"]);
}

function check_login() {
    if (!is_user_logged_in()) {
        header("Location: login.php");
        exit();
    }
}