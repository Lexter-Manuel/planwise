<?php   

if (file_exists('../config.php')) {
        include_once('../config.php');
}
if (file_exists('../../config.php')) {
        include_once('../../config.php');
}

if (isset($_GET['eventID'])) {
    $eventID = $_GET['eventID'];


    // Get venue details
    $sql = "SELECT * FROM events WHERE eventID = $eventID";
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
