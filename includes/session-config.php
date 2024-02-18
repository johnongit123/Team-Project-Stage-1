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

function regen_session_id_loggedin($loginId){
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

if (isset($_SESSION["login_id"])) {
    if (!isset($_SESSION["last_regeneration"]) || time() - $_SESSION["last_regeneration"] >= 60 * 30) {
        regen_session_id_loggedin($_SESSION["login_id"]);
    }
} else {
    if (!isset($_SESSION["last_regeneration"]) || time() - $_SESSION["last_regeneration"] >= 60 * 30) {
        regen_session_id();
    }
}
