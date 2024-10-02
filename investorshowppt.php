<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investor Dashboard</title>
    <link rel="stylesheet" href="investorshowpptstyle.css">
</head>
<body>
    <div class="container">
        <h1>Submitted PPT Files</h1>
        <ul id="pptList">
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

            // Retrieve submitted PPT files from the database
            $sql = "SELECT * FROM startup_forms";
            $result = $conn->query($sql);

            // Display the list of submitted PPT files with rating form
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $target_file = $row["pitch_deck"];
                    echo "<li>";
                    echo "<a href='$target_file'>". basename($target_file) ."</a>";
                    // Add a form to provide a rating
                    echo "<form action='rate_ppt.php' method='POST'>";
                    echo "<input type='hidden' name='ppt_file' value='$target_file'>";
                    echo "<label for='rating'>Rate (out of 10): </label>";
                    echo "<input type='number' name='rating' min='0' max='10' required>";
                    echo "<button type='submit'>Submit Rating</button>";
                    echo "</form>";
                    echo "</li>";
                }
            } else {
                echo "No submitted PPT files found.";
            }

            // Close database connection
            $conn->close();
            ?>
        </ul>
    </div>

    <script src="investorshowppt.js"></script>

    
    

        
</body>
</html>
