<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "startsup";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to check if the user exists
    $sql = "SELECT * FROM investors WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, verify password
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            // Password is correct, login successful
            echo "Login successful!";
            // Redirect or perform any other actions here
        } else {
            // Password is incorrect
            echo "Incorrect password!";
        }
    } else {
        // User not found
        echo "User not found!";
    }
}

// Close connection
$conn->close();
?>
