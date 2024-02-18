<?php
$dbhost = "localhost";
$dbuser = "team20";
$dbpass = "FjE*onBcO3)8KAwt";
$dbname = "team20";

try {
     // Attempt to establish a connection
    $con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
    
    // Check if the connection was successful
    if (!$con){
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
} catch(Exception $e) {
    // Connection failed, handle the exception
    die("Connection failed: " . $e->getMessage());
}

