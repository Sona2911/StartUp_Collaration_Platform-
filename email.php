<?php
// Include database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$database = "startsup";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to fetch startup email from the database
function getStartupEmail($startupName) {
    global $conn;

    // Prepare and execute SQL query to retrieve startup email
    $stmt = $conn->prepare("SELECT email FROM startup_forms WHERE startup_name = ?");
    $stmt->bind_param("s", $startupName);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the email
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['email'];
    } else {
        return null; // Return null if startup email not found
    }
}

// Function to send email to the startup
function sendEmailToStartup($startupEmail, $subject) {
    // Compose email message
    $to = $startupEmail;
    $subject = "Congratulations! Your PPT has been shortlisted";
    $message = "Dear Startup,\n\nCongratulations! Your PPT with subject '$subject' has been shortlisted.\n\nBest regards,\nThe Investor Team";
    $headers = "From: sonalikotlapure@gmail.com"; // Replace with your email address

    // Send email
    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent successfully to $startupEmail.";
    } else {
        echo "Failed to send email to $startupEmail.";
    }
}

// Example usage:
$startupName = "Example Startup"; // Replace with the actual startup name
$subject = "Example PPT Subject"; // Replace with the subject of the shortlisted PPT
$startupEmail = getStartupEmail($startupName);

if ($startupEmail) {
    sendEmailToStartup($startupEmail, $subject);
} else {
    echo "Startup email not found.";
}
?>