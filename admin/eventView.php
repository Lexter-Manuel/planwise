<?php
if (file_exists('../config.php')) {
    include_once('../config.php');
}
if (file_exists('../../config.php')) {
    include_once('../../config.php');
}

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

        /* Horizontal card style */
        .horizontal-card {
            display: flex;
            flex-direction: row;
            margin-bottom: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .horizontal-card img {
            max-width: 200px;
            height: auto;
            object-fit: cover;
        }

        .horizontal-card-body {
            flex: 1;
            padding: 15px;
        }

        .horizontal-card-footer {
            display: none;
            padding: 15px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
        }

        .horizontal-card:hover .horizontal-card-footer {
            display: block;
        }

        .event-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .extra-details {
            display: none;
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
                <li class="nav-item active">
                    <a class="nav-link" href="eventView.php">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="regHistory.php">History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<h1 class="text-center mt-4">Event List</h1>

<div class="container mt-4">
    <?php
    $sql = "SELECT e.eventID, e.event_name, e.description, e.date, e.time, e.organizer, e.status, e.ticket_availability, e.price, e.capacity, v.venue, v.venue_image 
            FROM events e 
            JOIN venues v ON e.venueID = v.venueID";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="horizontal-card" onclick="toggleDetails(<?php echo $row['eventID']; ?>)">
            <img src="<?php echo $row['venue_image']; ?>" alt="Venue Image">
            <div class="horizontal-card-body">
                <div class="event-title"><?php echo $row['event_name']; ?></div>
                <p><strong>Date:</strong> <?php echo $row['date']; ?> | <strong>Time:</strong> <?php echo $row['time']; ?></p>
                <p><strong>Venue:</strong> <?php echo $row['venue']; ?></p>
                <p><?php echo substr($row['description'], 0, 100) . '...'; ?></p>
            </div>
            <div id="details-<?php echo $row['eventID']; ?>" class="horizontal-card-footer">
                <p><strong>Organizer:</strong> <?php echo $row['organizer']; ?></p>
                <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
                <p><strong>Tickets:</strong> <?php echo $row['ticket_availability']; ?></p>
                <p><strong>Price:</strong> $<?php echo $row['price']; ?></p>
                <p><strong>Ticket Count:</strong> <?php echo $row['capacity']; ?></p>
                <?php if ($row['status'] == 'active' && $row['capacity'] > 0) { ?>
                    <button class="btn btn-primary mt-2" onclick="openRegistrationModal(<?php echo $row['eventID']; ?>, '<?php echo $row['event_name']; ?>', <?php echo $row['price']; ?>)">Register Now</button>
                <?php } else { ?>
                    <button class="btn btn-secondary mt-2" disabled>Registration Closed</button>
                <?php } ?>
            </div>

        </div>
        <?php
    }
    ?>
</div>

<!-- Registration Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrationModalLabel">Register for Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registrationForm">
                    <input type="hidden" id="eventID" name="eventID">
                    <div class="form-group">
                        <label for="eventName">Event Name</label>
                        <input type="text" id="eventName" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="ticketCount">Number of Tickets</label>
                        <input type="number" id="ticketCount" name="ticketCount" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="totalCost">Total Cost</label>
                        <input type="text" id="totalCost" class="form-control" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="registerEvent()">Register</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    // Open the Registration Modal
function openRegistrationModal(eventID, eventName, price) {
    $('#eventID').val(eventID);
    $('#eventName').val(eventName);
    $('#ticketCount').val(1);
    $('#totalCost').val(price);

    $('#ticketCount').on('input', function () {
        const count = $(this).val();
        $('#totalCost').val(count * price);
    });

    $('#registrationModal').modal('show');
}

// Handle Event Registration
function registerEvent() {
    const eventID = $('#eventID').val();
    const ticketCount = $('#ticketCount').val();

    $.ajax({
    url: 'register_event.php',
    type: 'POST',
    data: {
        eventID: eventID,
        ticketCount: ticketCount,
    },
    success: function (response) {
        console.log("Response:", response); // Debugging
        if (response === 'success') {
            Swal.fire('Registration Successful!', 'Your tickets have been booked.', 'success');
            $('#registrationModal').modal('hide');
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire('Error', response, 'error');
        }
    },
    error: function (xhr, status, error) {
        console.error("AJAX Error:", error); // Debugging
        Swal.fire('Error', 'An error occurred while registering.', 'error');
    }
});

}

</script>



<script>
    function toggleDetails(eventID) {
        var details = document.getElementById('details-' + eventID);
        if (details.style.display === "none" || details.style.display === "") {
            details.style.display = "block";
        } else {
            details.style.display = "none";
        }
    }
</script>

</body>
</html>
