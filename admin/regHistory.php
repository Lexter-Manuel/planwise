<?php
include_once('../config.php');
session_start();

if (!isset($_SESSION['userID'])) {
    echo 'User is not logged in.';
    exit;
}

$userID = $_SESSION['userID'];

$aggregatedSQL = "
    SELECT 
        e.event_name, 
        e.date, 
        e.time, 
        v.venue, 
        SUM(r.ticket_count) AS total_tickets, 
        SUM(r.total_price) AS total_price, 
        MIN(r.registration_date) AS first_registration_date, 
        r.status
    FROM 
        registrations r
    JOIN 
        events e ON r.eventID = e.eventID
    JOIN 
        venues v ON e.venueID = v.venueID
    WHERE 
        r.userID = ?
    GROUP BY 
        e.eventID, r.userID
";
$aggregatedStmt = $conn->prepare($aggregatedSQL);
$aggregatedStmt->bind_param('i', $userID);
$aggregatedStmt->execute();
$aggregatedResult = $aggregatedStmt->get_result();
$aggregatedRows = $aggregatedResult->fetch_all(MYSQLI_ASSOC); // Fetch all rows at once
$aggregatedStmt->free_result(); // Free result memory
$aggregatedStmt->close(); // Close the prepared statement

// Detailed Query
$detailedSQL = "
    SELECT 
        e.event_name, 
        e.date, 
        e.time, 
        v.venue, 
        r.ticket_count, 
        r.total_price, 
        r.registration_date, 
        r.status
    FROM 
        registrations r
    JOIN 
        events e ON r.eventID = e.eventID
    JOIN 
        venues v ON e.venueID = v.venueID
    WHERE 
        r.userID = ?
";
$detailedStmt = $conn->prepare($detailedSQL);
$detailedStmt->bind_param('i', $userID);
$detailedStmt->execute();
$detailedResult = $detailedStmt->get_result();
$detailedRows = $detailedResult->fetch_all(MYSQLI_ASSOC); // Fetch all rows at once
$detailedStmt->free_result(); // Free result memory
$detailedStmt->close(); // Close the prepared statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
    <title>PlanWise</title>


    <style>
        body {
    background-color: #FFCDD2;
}

.bg-black {
    background-color: #000;
}

#logo {
    width: 30px;
    height: 30px;
    border-radius: 4px;
}

.navbar-brand {
    padding: 14px 20px;
    font-size: 16px;
}

.navbar-nav {
    width: 100%;
}

.nav-item {
    padding: 6px 14px;
    text-align: center;
}

.nav-link {
    padding-bottom: 10px; 
}

.v-line {
    background-color: gray;
    width: 1px;
    height: 20px;
}

.navbar-collapse.collapse.in {
    display: block !important;
}

@media (max-width: 576px) {
    .nav-item {
        width: 100%;
        text-align: left;
    }

    .v-line {
        display: none;
    }
}
    </style>
</head>
<body>
<div class="container-fluid px-0">
    <nav class="navbar navbar-expand-sm navbar-dark bg-black py-0 px-0">
        <a class="navbar-brand" href="#"><img id="logo" src="https://i.imgur.com/K7Nwq4V.jpg"> &nbsp;&nbsp;&nbsp;Acme Inc</a>
        <span class="v-line"></span>
        <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="homepage.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eventView.php">Events</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="regHistory.php" >History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Analytics</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<div class="container mt-4">
    <h2 class="text-center">Registration History</h2><hr>
    <div class="text-center mb-4">
        <button id="btn-aggregated" class="btn btn-primary">Aggregated View</button>
        <button id="btn-detailed" class="btn btn-secondary">Detailed View</button>
    </div>
        
    <!-- Aggregated Registration Table -->
    <div id="aggregated-view" style="display: block;">
        <h3>Aggregated Registrations</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Total Tickets</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>First Registered On</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1;
                foreach ($aggregatedRows as $row) {
                    echo "<tr>
                        <td>{$counter}</td>
                        <td>{$row['event_name']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['time']}</td>
                        <td>{$row['venue']}</td>
                        <td>{$row['total_tickets']}</td>
                        <td>$" . number_format($row['total_price'], 2) . "</td>
                        <td>{$row['status']}</td>
                        <td>{$row['first_registration_date']}</td>
                    </tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Detailed Registration Table -->
    <div id="detailed-view" style="display: none;">
        <h3>Detailed Registrations</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Tickets</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Registered On</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1;
                foreach ($detailedRows as $row) {
                    echo "<tr>
                        <td>{$counter}</td>
                        <td>{$row['event_name']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['time']}</td>
                        <td>{$row['venue']}</td>
                        <td>{$row['ticket_count']}</td>
                        <td>$" . number_format($row['total_price'], 2) . "</td>
                        <td>{$row['status']}</td>
                        <td>{$row['registration_date']}</td>
                    </tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
    <script>
    document.getElementById('btn-aggregated').addEventListener('click', function () {
        document.getElementById('aggregated-view').style.display = 'block';
        document.getElementById('detailed-view').style.display = 'none';
        this.classList.add('btn-primary');
        this.classList.remove('btn-secondary');
        document.getElementById('btn-detailed').classList.add('btn-secondary');
        document.getElementById('btn-detailed').classList.remove('btn-primary');
    });

    document.getElementById('btn-detailed').addEventListener('click', function () {
        document.getElementById('aggregated-view').style.display = 'none';
        document.getElementById('detailed-view').style.display = 'block';
        this.classList.add('btn-primary');
        this.classList.remove('btn-secondary');
        document.getElementById('btn-aggregated').classList.add('btn-secondary');
        document.getElementById('btn-aggregated').classList.remove('btn-primary');
    });
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>