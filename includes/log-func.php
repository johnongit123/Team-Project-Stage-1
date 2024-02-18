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
    $hashed_password = get_password($email);

    if($hashed_password === null || !password_verify($pwd, $hashed_password)) {
        return true;
    } else {
        return false;
    }
}

