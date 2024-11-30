<?php
// Include database connection
include('../config.php');

// Check if eventID is set
if (isset($_POST['eventID'])) {
    $eventID = $_POST['eventID'];

    // Sanitize input
    $eventID = mysqli_real_escape_string($conn, $eventID);

    // SQL query to delete the event by ID
    $query = "DELETE FROM events WHERE eventID = ?";

    // Prepare statement
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameter
        $stmt->bind_param("i", $eventID);

        // Execute the query
        if ($stmt->execute()) {
            echo "success";  // Deletion successful
        } else {
            // Log SQL error with specific error message
            error_log("SQL Error: " . $stmt->error);
            echo "error: " . $stmt->error;  // Return detailed error message
        }

        // Close statement
        $stmt->close();
    } else {
        // If the query couldn't be prepared
        error_log("Query Preparation Error: " . $conn->error);
        echo "error: " . $conn->error;  // Return detailed error message
    }
} else {
    echo "No eventID provided";  // Return this if eventID is missing
}
?>
