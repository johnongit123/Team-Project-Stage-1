<?php
// Establish a connection to your SQL database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Hello";
// Execute query to fetch data
$sql = "SELECT id, title, date, comments FROM threads";
$result = $conn->query($sql);

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Generate HTML dynamically using fetched data
        echo '<li class="row">
                <a href="/thread.html?id=' . $row["id"] . '">
                    <h4 class="title">' . $row["title"] . '</h4>
                    <div class="bottom">
                        <p class="timestamp">' . date("Y-m-d H:i:s", strtotime($row["date"])) . '</p>
                        <p class="comment-count">' . count(json_decode($row["comments"])) . ' comments</p>
                    </div>
                </a>
              </li>';
    }
} else {
    echo "0 results";
}
$conn->close();
?>

