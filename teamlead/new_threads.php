<?php
require_once '../includes/session-config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $thread_title = $_POST['thread_name'];
    $author = $_POST['author_name'];
    $content = $_POST['content'];
    $thread_id = rand(100, 999);

    try {
        require_once '../includes/dbh.php';
        $errors = [];

        if (strlen($thread_title) > 29) {
            $errors['thread_name'] = 'Thread name must be less than 30 characters.';
        }

        if(empty($content) || empty($thread_title) || empty($author)){
            $errors["empty_input"] = "Please fill in all fields!";
        }

        if (str_word_count($content) > 300) {
            $errors['content'] = 'Thread content cannot exceed 300 words.';
        }

        if (does_thread_exist($thread_id, $content, $thread_title) > 0){
            $errors['exists'] = 'This Topic has already been discussed OR the id generated is already in use';
        }

        if (!empty($errors)){
            $_SESSION["errors_thread"] = $errors;
            header("Location: threads.php");
            die();
        } else {
            create_thread($thread_id, $thread_title, $author, $content);

            $_SESSION["success"] = "Thread has been created successfully!";
            header("Location: threads.php");
            die();
        }
    } catch(Exception $e){
        die("Query failed: " . $e->getMessage());
    }
}


function success_thread(){
    if (isset($_SESSION["success"])){
        $success_message= $_SESSION["success"];
        unset($_SESSION["success"]);
        echo '<p class="form-success">'. $success_message . '</p>';
    }
}

function check_thread_errors(){
    if (isset($_SESSION["errors_thread"])){
        $errors = $_SESSION["errors_thread"];
        unset($_SESSION["errors_thread"]);
        foreach($errors as $error) {
            echo ' <P class="form-error">' . $error . '</p>';
        }
    }
}

function does_thread_exist($thread_id, $content, $thread_title){
    global $con;

    $query = "Select COUNT(*) From Threads WHERE title = ? OR content = ? OR thread_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssi", $thread_title, $content, $thread_id);
    $stmt->execute();

    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    
    return $count;
}



function create_thread($thread_id, $thread_title, $author, $content){
    global $con;
    $query = "INSERT INTO Threads (thread_id, title, author, date, content)
               VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("isss", $thread_id, $thread_title, $author, $content);
    $stmt->execute();
    $thread_id = $stmt->insert_id;
    $stmt->close();
}
