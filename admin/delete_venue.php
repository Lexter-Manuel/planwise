<?php
// Include database connection
include('../config.php');

// Check if venueID is set
if (isset($_POST['venueID'])) {
    $venueID = $_POST['venueID'];

    // Sanitize input
    $venueID = mysqli_real_escape_string($conn, $venueID);

    // SQL query to delete the venue by ID
    $query = "DELETE FROM venues WHERE venueID = ?";
    
    // Prepare statement
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameter
        $stmt->bind_param("i", $venueID);

        // Execute the query
        if ($stmt->execute()) {
            echo "success";
        } else {
            // Log errors if any
            error_log("SQL Error: " . $stmt->error);
            echo "error";
        }

        // Close statement
        $stmt->close();
    } else {
        // If the query couldn't be prepared
        error_log("Query Preparation Error: " . $conn->error);
        echo "error";
    }
} else {
    echo "No venueID provided";
}
?>
