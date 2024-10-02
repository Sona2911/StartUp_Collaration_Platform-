<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require 'vendor/autoload.php';

// Handle rating submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Retrieve rating and PPT file from the form submission
    $rating = $_POST["rating"];
    $target_file = $_POST["ppt_file"];

    // Update the database with the rating
    $update_sql = "UPDATE startup_forms SET rating = $rating WHERE pitch_deck = '$target_file'";
    if ($conn->query($update_sql) === TRUE) {
        // Store the rating in the new table
        $insert_sql = "INSERT INTO ppt_ratings (pitch_deck, rating) VALUES ('$target_file', $rating)";
        if ($conn->query($insert_sql) === TRUE) {
            // Fetch the startup's email address
            $startupEmailQuery = "SELECT email FROM startup_forms WHERE pitch_deck = '$target_file'";
            $result = $conn->query($startupEmailQuery);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $startupEmail = $row["email"];

                // Send email using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'sonalikotlapure@gmail.com'; // SMTP username
                    $mail->Password = 'myyb vpzg tibu lobq'; // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('sonalikotlapure@gmail.com', 'Investor Team');
                    $mail->addAddress($startupEmail);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your PPT has been rated';
                    $mail->Body = "Dear Startup,<br><br>Your PPT ($target_file) has been rated with a score of $rating.<br><br>Best regards,<br>The Investor Team";

                    $mail->send();
                    echo "<script>alert('Rating submitted successfully! Email sent to startup.');</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Error sending email: {$mail->ErrorInfo}');</script>";
                }
            } else {
                echo "<script>alert('Error: Startup email not found.');</script>";
            }
        } else {
            echo "<script>alert('Error storing rating: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error updating rating: " . $conn->error . "');</script>";
    }

    // Close database connection
    $conn->close();
}
?>
