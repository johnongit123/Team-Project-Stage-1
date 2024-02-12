<?php

$servername='35.246.76.223';
$dbname='team20';
$db_username = "team20";
$db_pass = "password";


try {
    $pdo = new PDO("mysql:host=$servername;,dbname=$dbname",$db_username,
    $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>