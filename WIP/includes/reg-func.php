<?php

declare(strict_types=1);


// error-detection section
// check empty input fields
function is_input_empty(string $first_n, string $last_n, string $email, string $pwd, string $c_pwd) {
    if(empty($first_n) || empty($last_n) || empty($email)|| empty($pwd)|| empty($c_pwd)){
        return true;
    } else {
        return false;
    }
}



function is_user_email_invalid(string $first_n, string $last_n, string $email){
    if (!get_useremail($first_n, $last_n,$email)) {
        return true;
    } else {
        return false;
    }
}

//checks registration column in database 
//to see if users are(Y) or are not(N) already registered
function is_reg_unavailable(string $first_n, string $last_n, string $email) {
    $result = get_registration_status($first_n, $last_n, $email);
    if ($result == "Y") {
        return true;
    } else {
        return false;
    }
}

//checks database to ensure the user is already in the database 
function is_name_invalid(string $first_n, string $last_n){
    $result = get_full_name($first_n, $last_n);
    if($result === null) {
        return true;
    } else {
        return false;
    }
}

