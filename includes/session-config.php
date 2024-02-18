<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800,
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

session_start();

function is_user_logged_in() {
    return isset($_SESSION["login_id"]);
}

function check_login() {
    if (!is_user_logged_in()) {
        header("Location: ../login.php");
        exit();
    }
}




if (isset($_SESSION["login_id"])) {
    if(!isset($_SESSION["last_regeneration"])){
        regen_session_id_loggedin();
    } else{
        $interval = 60 * 30;
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            regen_session_id_loggedin();
        }
    }
} else {
    if(!isset($_SESSION["last_regeneration"])){
        regen_session_id();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            regen_session_id();
        }
    }
}



function regen_session_id_loggedin(){
    session_regenerate_id(true);
    
    $loginId = $_SESSION["login_id"];
    $newSessionId = session_create_id();
    $sessionId = $newSessionId . "_". $loginId;
    session_id($sessionId);

    $_SESSION["last_regeneration"] = time();
}

function regen_session_id(){
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}
