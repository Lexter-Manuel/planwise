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

$sql = "SELECT * FROM venues";
$result = $conn->query($sql);

$venueID = isset($_GET['venueid']) ? $_GET['venueid'] : 0;

var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['venueID']) && isset($_POST['venue']) && isset($_POST['address']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['capacity'])) {

        // Sanitize the inputs
        $venueID = $_POST['venueID'];
        $venue = mysqli_real_escape_string($conn, urldecode($_POST['venue']));
        $address = mysqli_real_escape_string($conn, urldecode($_POST['address']));
        $description = mysqli_real_escape_string($conn, urldecode($_POST['description']));
        $price = mysqli_real_escape_string($conn, urldecode($_POST['price']));
        $capacity = mysqli_real_escape_string($conn, urldecode($_POST['capacity']));
        $venue_availability = mysqli_real_escape_string($conn, urldecode($_POST['venue_availability']));

        // Handle the image upload
        if (isset($_FILES['venue_image']) && $_FILES['venue_image']['error'] == 0) {
            $image = $_FILES['venue_image'];
            
            // Ensure the target directory exists
            $targetDir = 'images/venues/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true); // Create the directory if it doesn't exist
            }

            // Sanitize file name to prevent invalid characters
            $filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($image['name']));
            $imagePath = $targetDir . $filename;

            // Validate file type (only allow images)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($image['type'], $allowedTypes)) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                    $venue_image = $imagePath;
                } else {
                    echo "Error: Failed to move uploaded file.";
                    exit;
                }
            } else {
                echo "Error: Unsupported file type. Only JPG, PNG, and GIF are allowed.";
                exit;
            }
        } else {
            $venue_image = '';  // If no image is uploaded
        }

        // Insert into database
        if ($venueID == 0) {
            $sql = "INSERT INTO venues (venue, address, description, price, capacity, venue_availability, venue_image)
                     VALUES ('$venue', '$address', '$description', '$price', '$capacity', '$venue_availability', '$venue_image')";
        } else {
            $sql = "UPDATE venues 
                     SET venue = '$venue', address = '$address', description = '$description', price = '$price', 
                         capacity = '$capacity', venue_availability = '$venue_availability', venue_image = '$venue_image' 
                     WHERE venueID = '$venueID'";
        }

        if (mysqli_query($conn, $sql)) {
            echo "Venue added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

    } else {
        echo "Missing parameters.";
    }
}

?>


<link rel="stylesheet" href="style/style.css">

<style>
    .form-group {
        margin: 1rem 0;
    }
</style>

<h1 class="text-center">Venue List</h1>

<div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVenueModal">Add A Venue</button>
</div>

<table id="venuesTable" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Venue</th>
                <th>Address</th>
                <th>Venue Description</th>
                <th>Price</th>
                <th>Capacity</th>
                <th>Venue Availability</th>
                <th>Image Preview</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['venueID']; ?></td>
                <td><?php echo $row['venue']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['capacity']; ?></td>
                <td><?php echo $row['venue_availability']; ?></td>
                <td><img src="<?php echo $row['venue_image']; ?>" alt="Image Preview" style="max-width: 100px; height: auto;"></td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="loadVenueForEdit(<?php echo $row['venueID']; ?>)">EDIT</button>
                    <button type="button" class="btn btn-danger" onclick="deleteVenue(<?php echo $row['venueID'];?>)">DELETE</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>





<!-- Modal -->
    <div class="modal fade" id="addVenueModal" tabindex="-1" role="dialog" aria-labelledby="addVenueModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Venue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group row">
                        <label for="inputVenue" class="col-sm-2 col-form-label">Venue ID</label>
                        <div class="col-sm-10">
                            <input type="text" id="venueID" value="<?php echo $venueID ? $venueID : 0; ?>" disabled />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputVenue" class="col-sm-2 col-form-label">Venue</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputVenue" placeholder="Venue">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputAddress" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputAddress" placeholder="Address">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputDesc" class="col-sm-2 col-form-label">Venue Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="inputDesc" placeholder="Venue Description" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPrice" class="col-sm-2 col-form-label">Price</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPrice" placeholder="Price">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputCapacity" class="col-sm-2 col-form-label">Capacity</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputCapacity" placeholder="Capacity">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputAvailability" class="col-sm-2 col-form-label">Venue Availability</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="inputAvailability" required>
                                <option value="" disabled selected>Select Availability</option>
                                <option value="booked">Booked</option>
                                <option value="available">Available</option>
                                <option value="under_maintenance">Under Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputVenueImage" class="col-sm-2 col-form-label">Image Preview</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="inputVenueImage" name="venue_image" accept="image/*" onchange="previewImage(event)" required>
                            <br>
                            <img id="imagePreview" src="" alt="Image Preview" style="display:none; max-width: 100%; height: auto;">
                        </div>
                    </div>
                    <script>
                        function previewImage(event) {
                            const reader = new FileReader();
                            reader.onload = function() {
                                const output = document.getElementById('imagePreview');
                                output.src = reader.result;
                                output.style.display = 'block';  // Show the image once loaded
                                console.log('preview loaded')
                            };
                            reader.readAsDataURL(event.target.files[0]);
                        }
                    </script>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="addEditBtn" class="btn btn-primary" onclick="addEditVenue()" >ADD</button>
            </div>
        </div>
    </div>
    </div>








