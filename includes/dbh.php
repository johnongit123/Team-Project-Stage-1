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
    } else{
        echo "we connected!";
        $query = "SELECT * from employee";
        $result = $con->query($query);
        // Check if the query was successful
        if ($result) {
            // Fetch data from the result object and display it
            while ($row = $result->fetch_assoc()) {
                // Assuming 'employee' table has a column named 'name'
                echo "Employee Name: " . $row['first_name'] . "<br>";
            }
        } else {
            echo "Error executing query: " . $con->error;
        }
    }
} catch(Exception $e) {
    // Connection failed, handle the exception
    die("Connection failed: " . $e->getMessage());
}

