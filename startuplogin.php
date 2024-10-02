<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password is set
$database = "startsup";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch row
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, redirect to dashboard or any other page
            header("Location: index.html");
            exit();
        } else {
            // Password is incorrect
            echo "Incorrect password.";
        }
    } else {
        // User with provided email does not exist
        echo "User with provided email does not exist.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
