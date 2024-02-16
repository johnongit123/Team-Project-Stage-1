<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        require_once '../includes/dbh.php';
        $errors = [];








        
    } catch(Exception $e) {
        die("Query failed: " . $e->getMessage());
    }
}