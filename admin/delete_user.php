<?php
// Include database connection
include('../config.php');

// Check if userID and reason are set
if (isset($_POST['userID']) && isset($_POST['reason'])) {
    $userID = $_POST['userID'];
    $reason = $_POST['reason'];

    // Get the logged-in user's name (assuming it's stored in session)
    session_start();
    $archivedBy = $_SESSION['username'];  // Assuming the username is stored in the session

    // Sanitize inputs
    $userID = mysqli_real_escape_string($conn, $userID);
    $reason = mysqli_real_escape_string($conn, $reason);
    $archivedBy = mysqli_real_escape_string($conn, $archivedBy);

    // Fetch the user data to archive it before deletion
    $query = "SELECT * FROM users WHERE userID = ?";

    // Prepare statement for fetching user data
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameter
        $stmt->bind_param("i", $userID);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Archive the user by inserting into the archive table
            $archiveQuery = "INSERT INTO archived_users 
                (archivedUserID, fname, mname, lname, username, password, email, role, birthday, contact_number, gender, archived_at, archived_by, reason) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";

            if ($archiveStmt = $conn->prepare($archiveQuery)) {
                // Bind the parameters
                $archiveStmt->bind_param("issssssssssss", 
                    $user['userID'], $user['fname'], $user['mname'], $user['lname'], $user['username'], 
                    $user['password'], $user['email'], $user['role'], $user['birthday'], $user['contact_number'], 
                    $user['gender'], $archivedBy, $reason);

                // Execute the archive query
                if ($archiveStmt->execute()) {
                    // After successful archive, delete the user from the main table
                    $deleteQuery = "DELETE FROM users WHERE userID = ?";

                    if ($deleteStmt = $conn->prepare($deleteQuery)) {
                        // Bind the parameter for deletion
                        $deleteStmt->bind_param("i", $userID);

                        // Execute the deletion query
                        if ($deleteStmt->execute()) {
                            echo "success";  // Deletion and archiving successful
                        } else {
                            echo "error deleting: " . $deleteStmt->error;  // Error during deletion
                        }

                        $deleteStmt->close();
                    } else {
                        echo "error preparing delete query: " . $conn->error;  // Error preparing delete query
                    }
                } else {
                    echo "error archiving: " . $archiveStmt->error;  // Error during archiving
                }

                $archiveStmt->close();
            } else {
                echo "error preparing archive query: " . $conn->error;  // Error preparing archive query
            }

        } else {
            echo "User not found";  // If user does not exist
        }

        // Close statement
        $stmt->close();
    } else {
        echo "error preparing select query: " . $conn->error;  // Error preparing select query
    }
} else {
    echo "No userID or reason provided";  // Return this if userID or reason is missing
}
?>
