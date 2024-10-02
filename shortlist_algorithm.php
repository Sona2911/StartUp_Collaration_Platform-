<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$database = "startsup";

// Function to calculate score for each PPT file based on certain criteria
function calculateScore($pptFile) {
    // Placeholder score calculation, replace with actual logic

    // Get the contents of the PPT file (you may need a library to parse PPT files)
    $pptContents = file_get_contents($pptFile);

    // Example criteria for scoring
    $criteriaMet = 0;

    // Define keywords and their corresponding scoring criteria
    $keywords = array(
        'innovative idea' => 1,
        'market potential' => 1,
        'financial projections' => 1,
        'customer segmentation' => 1,
        'competitive analysis' => 1,
        'unique value proposition' => 1,
        'revenue model' => 1,
        'growth strategy' => 1,
        'team expertise' => 1,
        'product roadmap' => 1,
        'user experience' => 1,
        'scalability' => 1,
        'exit strategy' => 1
    );

    // Check if each keyword is present in the PPT contents
    foreach ($keywords as $keyword => $score) {
        if (strpos($pptContents, $keyword) !== false) {
            $criteriaMet += $score;
        }
    }

    // Calculate score based on the total number of criteria met
    $totalCriteria = count($keywords); // Total number of criteria
    $score = $criteriaMet / $totalCriteria;

    return $score;
}

// Function to filter and shortlist PPT files
function calculateShortlist() {
    global $servername, $username, $password, $database;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Directory where PPT files are stored
    $pptDirectory = "uploads/";

    // Get all PPT files in the directory
    $pptFiles = glob($pptDirectory . "*.ppt");

    // Array to store scores for each PPT file
    $scores = array();

    // Calculate score for each PPT file
    foreach ($pptFiles as $pptFile) {
        $score = calculateScore($pptFile);
        $scores[$pptFile] = $score;
    }

    // Sort the PPT files based on their scores (descending order)
    arsort($scores);

    // Shortlist the top 5 PPT files
    $shortlistedPPTs = array_slice($scores, 0, 5, true);

    // Insert the shortlisted PPT files into the database
    foreach ($shortlistedPPTs as $pptFile => $score) {
        $sql = "INSERT INTO shortlisted_ppts (file_path, score) VALUES ('$pptFile', $score)";
        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close database connection
    $conn->close();

    // Return the shortlisted PPT files
    return $shortlistedPPTs;
}

// Call the calculateShortlist() function to trigger shortlisting
$shortlistedPPTs = calculateShortlist();
?>