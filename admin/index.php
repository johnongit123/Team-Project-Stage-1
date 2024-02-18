<?php
$servername = "localhost";
$username = "team20";
$password = "FjE*onBcO3)8KAwt";
$dbname = "team20";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->   
</head>
</html>
<body>

<div class="main">';


// Get the URL parameter 'id'
$threadID = isset($_GET['id']) ? $_GET['id'] : null;
$sql = "SELECT TITLE, AUTHOR, CONTENT FROM Threads WHERE THREADID = $threadID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<div class="top-bar">';
        echo '<h1 class="thread-title">' . $row["TITLE"] . '       By ' . $row["AUTHOR"] . '</h1>';
        echo '</div>';
        echo '<p class="thread-content">' . $row["CONTENT"] . '</p>';
        ?>
        <img src="<?php echo $imagePath; ?>" alt="Image">
        <?php
    }
} else {
    echo "0 results";
}

$conn->close(); // Close the database connection

echo '</div>';
echo '</body>';

?>




