<?php
require_once('config.php');

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    // Check if the verification code matches
    $query = "SELECT * FROM users WHERE verification_code = '$verification_code'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Code matched, update the user's email_verified status
        $updateQuery = "UPDATE users SET email_verified = 1 WHERE verification_code = '$verification_code'";
        if ($conn->query($updateQuery) === TRUE) {
            echo "Email verified successfully! You can now log in.";
        } else {
            echo "Error: Unable to verify email.";
        }
    } else {
        echo "Invalid verification code.";
    }
} else {
    echo "No verification code provided.";
}
?>
