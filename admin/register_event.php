<?php
include_once('../config.php');

session_start();

if (!isset($_SESSION['userID'])) {
    echo 'User is not logged in.';
    exit;
}

if (isset($_POST['eventID']) && isset($_POST['ticketCount'])) {
    error_log("Received POST data: eventID=" . $_POST['eventID'] . ", ticketCount=" . $_POST['ticketCount']);
    $eventID = intval($_POST['eventID']);
    $ticketCount = intval($_POST['ticketCount']);

    // Fetch event details
    $sql = "SELECT capacity, price, status FROM events WHERE eventID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if ($event['status'] !== 'active') {
        echo 'Event is inactive.';
        exit;
    }

    if ($ticketCount > $event['capacity']) {
        echo 'Not enough tickets available.';
        exit;
    }

    $totalPrice = $ticketCount * $event['price'];

    // Deduct tickets and insert registration record
    $conn->begin_transaction();
    try {
        // Update ticket availability
        $updateSQL = "UPDATE events SET capacity = capacity - ? WHERE eventID = ?";
        $updateStmt = $conn->prepare($updateSQL);
        if (!$updateStmt) {
            error_log("SQL Error: " . $conn->error);
            echo 'Database error.';
            exit;
        }
        $updateStmt->bind_param('ii', $ticketCount, $eventID);
        $updateStmt->execute();

        // Insert into registrations
        $insertSQL = "INSERT INTO registrations (eventID, userID, ticket_count, total_price) 
        VALUES (?, ?, ?, ?)";
        $totalPrice = $ticketCount * $event['price']; // Ensure this calculation is correct
        $insertStmt = $conn->prepare($insertSQL);
        $userID = $_SESSION['userID']; // Assuming user is logged in
        $insertStmt->bind_param('iiid', $eventID, $userID, $ticketCount, $totalPrice);
        $insertStmt->execute();

        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        $conn->rollback();
        echo 'Error: ' . $e->getMessage();
    }
} else {
    error_log("POST data not received properly.");
    echo 'Invalid request.';
}
?>
