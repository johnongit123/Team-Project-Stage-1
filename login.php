<?php
<?php
$servername = "localhost"; // Change this to your MySQL server address
$username = "team20"; // Change this to your MySQL username
$password = "password"; // Change this to your MySQL password
$database = "Team20"; // Change this to the name of your MySQL database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

// Close connection
$conn->close();
?>

    // Retrieve values from the login form

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Preparation and binding sql statements
    $stmt = $conn->prepare("SELECT * FROM personnel WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);

    // query execution
    $stmt->execute();

    // get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Valid credentials, set session and redirect based on job role
        $row = $result->fetch_assoc();
        $_SESSION['loggedInUser'] = $email;
        $jobRole = $row['job_role'];
        switch ($jobRole) {
            case 'admin':
                $redirect ='a_dash.html';
                break;
            case 'team_lead':
                $redirect ='faq.html'; //TEMPORRARY***************
                break;
            case 'employee':
                $redirect ='reg_dash.html';
                break;
            default:
                // Handle any other cases
                echo "Job role not present.";
                exit();
        }
        //redirects
        header("Location: $redirect");
        exit();
    } else {
        // Invalid credentials
        $_SESSION['loginError'] = "Invalid email or password. Please try again.";
        header("Location: login.html");
    }
    //closing connections
    $stmt->close();
    $conn->close();  
} else {
    header("Location: login.html");
    die();
}
?>
