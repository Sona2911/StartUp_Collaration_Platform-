<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startup Dashboard</title>
</head>
<body>
    <h1>Your PPT Ratings</h1>
    <ul>
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

        // Retrieve ratings for submitted PPT files from the database
               
        $sql = "SELECT pitch_deck , rating FROM ppt_ratings WHERE rating IS NOT NULL";
        $result = $conn->query($sql);

        // Display the list of submitted PPT files with their ratings
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $target_file = $row["pitch_deck"];
                $rating = $row["rating"];
                echo "<li>File: <a href='$target_file'>" . basename($target_file) . "</a>, Rating: $rating</li>";
            }
        } else {
            echo "<li>No ratings available.</li>";
        }

        // Close database connection
        $conn->close();
        ?>
    </ul>
</body>
</html>