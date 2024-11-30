<?php
if (isset($_GET['venueID'])) {
    $venueID = $_GET['venueID'];

    if (file_exists('../config.php')) {
        include_once('../config.php');
    }
    if (file_exists('../../config.php')) {
        include_once('../../config.php');
    }

    // Get venue details
    $sql = "SELECT * FROM venues WHERE venueID = $venueID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the venue details
        $venue = $result->fetch_assoc();
        // Return the details as a JSON response
        echo json_encode($venue);
    } else {
        echo json_encode(['error' => 'Venue not found']);
    }
}
?>
