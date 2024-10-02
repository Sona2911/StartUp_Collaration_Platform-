<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO pitch_submission (startup_name, founder_name, email, pitch) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $startup_name, $founder_name, $email, $pitch);

    // Set parameters and execute
    $startup_name = $_POST['startup_name'];
    $founder_name = $_POST['founder_name'];
    $email = $_POST['email'];
    $pitch = $_POST['pitch'];

    if ($stmt->execute()) {
        // Upload pitch file
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["pitch_file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["pitch_file"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "pdf") {
            echo "Sorry, only PPT, PPTX, and PDF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["pitch_file"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars(basename($_FILES["pitch_file"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
