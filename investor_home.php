<?php
// Database configuration
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

// Retrieve shortlisted PPT files from the database
$sql = "SELECT * FROM shortlisted_ppts";
$result = $conn->query($sql);

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investor Home Page</title>
</head>
<body>
    <h1>Shortlisted PPT Files</h1>
    <ul>
        <?php
        // Display shortlisted PPT files as links
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $filePath = $row["file_path"];
                $score = $row["score"];
                echo "<li><a href='$filePath'>PPT File (Score: $score)</a></li>";
            }
        } else {
            echo "No shortlisted PPT files found.";
        }
        ?>
    </ul>
</body>
</html>
