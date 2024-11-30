<?php
if (file_exists('../config.php')) {
    include_once('../config.php');
}
if (file_exists('../../config.php')) {
    include_once('../../config.php');
}
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch existing events
$sql = "SELECT e.eventID, e.event_name, e.description, e.date, e.time, e.organizer,
        e.status, e.ticket_availability, e.price, e.capacity, v.venue 
        FROM events e 
        JOIN venues v ON e.venueID = v.venueID";
$result = $conn->query($sql);

// Set $eventID from POST data for updates
$eventID = isset($_POST['eventID']) ? intval($_POST['eventID']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['event_name'], $_POST['description'], $_POST['date'], $_POST['time'], $_POST['venueID'], $_POST['organizer'], $_POST['status'], $_POST['ticket_availability'], $_POST['price'], $_POST['capacity'])) {


        $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $time = mysqli_real_escape_string($conn, $_POST['time']);
        $venueID = intval($_POST['venueID']);
        $organizer = mysqli_real_escape_string($conn, $_POST['organizer']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $ticket_availability = mysqli_real_escape_string($conn, $_POST['ticket_availability']);
        $price = floatval($_POST['price']);
        $capacity = intval($_POST['capacity']);

        // Determine whether to insert or update
        if ($eventID == 0) {
            $sql = "INSERT INTO events (event_name, description, date, time, organizer, status, ticket_availability, venueID, price, capacity) 
                            VALUES ('$event_name', '$description', '$date', '$time', '$organizer', '$status', '$ticket_availability', $venueID, $price, $capacity)";
        } else {
            $sql = "UPDATE events SET 
                        event_name='$event_name', 
                        description='$description', 
                        date='$date', 
                        time='$time', 
                        organizer='$organizer', 
                        status='$status', 
                        ticket_availability='$ticket_availability', 
                        venueID=$venueID, 
                        price=$price, 
                        capacity=$capacity 
                    WHERE eventID=$eventID";
        }

        if (mysqli_query($conn, $sql)) {
            echo "Event saved successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Missing parameters.";
    }
}


?>


<h1 class="text-center">Event List</h1>

<div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">Add An Event</button>
</div>

<table id="eventsTable" class="table table-striped nowrap" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Event Name</th>
            <th>Description</th>
            <th>Date</th>
            <th>Time</th>
            <th>Venue</th>
            <th>Organizer</th>
            <th>Status</th>
            <th>Ticket Availability</th>
            <th>Ticket Price</th>
            <th>Ticket Count</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['eventID']; ?></td>
            <td><?php echo $row['event_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['time']; ?></td>
            <td><?php echo $row['venue']; ?></td>
            <td><?php echo $row['organizer']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['ticket_availability']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['capacity']; ?></td>
            <td>
                <button class="btn btn-secondary" onclick="loadEventForEdit(<?php echo $row['eventID']; ?>)">Edit</button>
                <button class="btn btn-danger" onclick="deleteEvent(<?php echo $row['eventID']; ?>)">Delete</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Add/Edit Modal -->
<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group row">
                        <label for="eventID" class="col-sm-2 col-form-label">Event ID</label>
                        <div class="col-sm-10">
                            <input type="text" id="eventID" value="0" disabled />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEventName" class="col-sm-2 col-form-label">Event Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputEventName" placeholder="Event Name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEventDate" class="col-sm-2 col-form-label">Date</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="inputEventDate" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEventTime" class="col-sm-2 col-form-label">Time</label>
                        <div class="col-sm-10">
                            <input type="time" class="form-control" id="inputEventTime" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputVenueID" class="col-sm-2 col-form-label">Venue</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="inputVenueID" required>
                                <!-- Populate options dynamically with PHP -->
                                <?php
                                $venues = $conn->query("SELECT venueID, venue FROM venues");
                                while ($row = $venues->fetch_assoc()) {
                                    echo "<option value='{$row['venueID']}'>{$row['venue']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEventDesc" class="col-sm-2 col-form-label">Event Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="inputEventDesc" placeholder="Event Description" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputOrganizer" class="col-sm-2 col-form-label">Organizer</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputOrganizer" placeholder="Organizer" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputStatus" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="inputStatus" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputTicketAvail" class="col-sm-2 col-form-label">Ticket Availability</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="inputTicketAvail" required>
                                <option value="" disabled selected>Select Availability</option>
                                <option value="available">Available</option>
                                <option value="sold out">Sold Out</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEventPrice" class="col-sm-2 col-form-label">Price</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputEventPrice" placeholder="Event Price" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEventCapacity" class="col-sm-2 col-form-label">Ticket Count</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputEventCapacity" placeholder="Event Capacity" required> 
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="addEditEventBtn" class="btn btn-primary" onclick="addEditEvent()">Add</button>
            </div>
        </div>
    </div>
</div>
