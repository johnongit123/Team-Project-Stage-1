<?php

declare(strict_types=1);

// check empty input fields
function is_input_empty(string $email, string $pwd) {
    if(empty($email) || empty($pwd)) {
        return true;
    } else {
        return false;
    }
}


//Valid email (does it match database)
function is_email_wrong(string $email) {
    if(!get_email($email)) {
        return true;
    } else {
        return false;
    }
}


//Valid password (does it match database)
function is_password_wrong(string $pwd, string $email) {
    $pwd_h_db = get_password($email);

    if($pwd_h_db === null || !password_verify($pwd, $pwd_h_db)) {
        return true;
    } else {
        return false;
    }
}





