<?php
if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    if (file_exists('../config.php')) {
        include_once('../config.php');
    }
    if (file_exists('../../config.php')) {
        include_once('../../config.php');
    }

    // Get username details
    $sql = "SELECT * FROM users WHERE userID = $userID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the username details
        $username = $result->fetch_assoc();
        // Return the details as a JSON response
        echo json_encode($username);
    } else {
        echo json_encode(['error' => 'username not found']);
    }
}
?>
