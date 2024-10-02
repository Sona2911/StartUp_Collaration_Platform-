<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require 'vendor/autoload.php';

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
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
    $subject = $_POST["subject"];
    $startup_name = $_POST["startup_name"];
    $phone_sector = $_POST["phone_sector"];
    $fund_ask = $_POST["fund_ask"];
    $valuation_ask = $_POST["valuation_ask"];
    $location = $_POST["location"];
    $founder_linkedln_url = $_POST["founder_linkedln_url"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $referred_name = $_POST["referred_name"];
    $business_description = $_POST["business_description"];
    $message = $_POST["message"];

    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["pitch_deck"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["pitch_deck"]["size"] > 10000000) { // Adjust the file size limit as needed
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowed_types = ['pdf', 'ppt', 'pptx', 'doc', 'docx', 'txt'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only PDF, PPT, PPTX, DOC, DOCX, and TXT files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["pitch_deck"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars(basename($_FILES["pitch_deck"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO startup_forms (subject, startup_name, phone_sector, fund_ask, valuation_ask, location, founder_linkedln_url, name, email, phone, referred_name, business_description, message, pitch_deck) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", $subject, $startup_name, $phone_sector, $fund_ask, $valuation_ask, $location, $founder_linkedln_url, $name, $email, $phone, $referred_name, $business_description, $message, $target_file);

    // Execute the statement
    if ($stmt->execute()) {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;
            $mail->Username = 'sonalikotlapure@gmail.com'; // SMTP username
            $mail->Password = 'dzmz gvmv gjwc xalo'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port = 587; // TCP port to connect to

            //Recipients
            $mail->setFrom('sonalikotlapure@gmail.com', 'Investor Team');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Your PPT has been submitted';
            $mail->Body    = "Dear $name,<br><br>Thank you for submitting your pitch deck. Your PPT ($target_file) has been successfully uploaded.<br><br>Best regards,<br>The Investor Team";

            $mail->send();
            echo "<script>alert('Form submitted successfully! Email sent to startup.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
        }

        // Redirect to homepage or any other page
        header("Location: index.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the form is not submitted, redirect back to the form page or do something else
    header("Location: startuppersonal.html");
    exit();
}
?>
