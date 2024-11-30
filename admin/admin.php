<?php


if (file_exists('../config.php')) {
    include_once('../config.php');
}
if (file_exists('../../config.php')) {
    include_once('../../config.php');
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- styles -->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
                                
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Document</title>
</head>
<body>
    <div class='dashboard'>
        <div class="dashboard-nav">
            <header>
                <a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
                <a href="#" class="brand-logo"><i class="fas fa-anchor"></i> <span>PLANWISE</span></a>
            </header>
            <nav class="dashboard-nav-list">
                <a href="javascript:void()" onclick="loadPage('','content')" class="dashboard-nav-item">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                <a
                    href="javascript:void()" onclick="loadPage('','content')" class="dashboard-nav-item active"><i class="fas fa-tachometer-alt"></i> dashboard
                </a>
                <a href="javascript:void()" onclick="loadPage('venues.php?venueid=0','content')" class="dashboard-nav-item">
                    <i class="fas fa-file-upload"></i>
                    Venues 
                </a>
                <a href="javascript:void()" onclick="loadPage('events.php?eventid=0','content')" class="dashboard-nav-item">
                    <i class="fas fa-cogs"></i> 
                    Events 
                </a>
                <a href="javascript:void()" onclick="loadPage('users.php?userid=0','content')" class="dashboard-nav-item">
                    <i class="fas fa-user"></i> 
                    Users 
                </a>
            <div class="nav-item-divider"></div>
            <a
                 href="../logout.php" class="dashboard-nav-item"><i class="fas fa-sign-out-alt"></i> Logout </a>
            </nav>
        </div>
        <div class='dashboard-app'>
            <header class='dashboard-toolbar'><a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a></header>
            <div class='dashboard-content'>
                <div class='container' id="content" style="width:75vw">
                    <div class='card'>
                        <div class='card-header'>
                            <h1>Welcome back, Admin</h1>
                        </div>
                        <div class='card-body'>
                            <p>Your account type is: Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- loadpage function -->
<script>
    function loadPage(url, elementId) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById(elementId).innerHTML = xmlhttp.responseText;

                // Ensure DataTable reinitializes after content load
                setTimeout(() => {
                    if ($.fn.DataTable.isDataTable('#venuesTable')) {
                        $('#venuesTable').DataTable().destroy();
                    }
                    $('#venuesTable').DataTable({
                        responsive: true,
                    });
                }, 200);
                setTimeout(() => {
                    if ($.fn.DataTable.isDataTable('#eventsTable')) {
                        $('#eventsTable').DataTable().destroy();
                    }
                    $('#eventsTable').DataTable({
                        responsive: true,
                    });
                }, 200);
                setTimeout(() => {
                    if ($.fn.DataTable.isDataTable('#usersTable')) {
                        $('#usersTable').DataTable().destroy();
                    }
                    $('#usersTable').DataTable({
                        responsive: true,
                    });
                }, 200);
                setTimeout(() => {
                    if ($.fn.DataTable.isDataTable('#archivedUsersTable')) {
                        $('#archivedUsersTable').DataTable().destroy();
                    }
                    $('#archivedUsersTable').DataTable({
                        responsive: true,
                    });
                }, 200);
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }

</script>

<!-- addEdit functions -->
<script>
function addEditVenue() {
    var venue = document.getElementById('inputVenue').value;
    var address = document.getElementById('inputAddress').value;
    var description = document.getElementById('inputDesc').value;
    var price = document.getElementById('inputPrice').value;
    var capacity = document.getElementById('inputCapacity').value;
    var venue_availability = document.getElementById('inputAvailability').value;
    var venue_image = document.getElementById('inputVenueImage').files[0];
    var venueid = document.getElementById('venueID').value || 0;

    if (venue !== '' && address !== '' && description !== '' && price !== '' && capacity !== '') {
        
        if (venue_image) {
            var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(venue_image.type)) {
                swal.fire('Error', 'Invalid image type. Only JPG, PNG, and GIF are allowed.', 'error');
                return; // Prevent form submission
            }
            if (venue_image.size > 5 * 1024 * 1024) { // 5MB limit
                swal.fire('Error', 'Image size exceeds the allowed limit of 5MB.', 'error');
                return; // Prevent form submission
            }
        }

        swal.fire({
            title: 'Events',
            text: 'Are you sure you want to ' + (venueid == 0 ? 'add' : 'update') + ' this event?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: venueid == 0 ? 'Yes, add it!' : 'Yes, update it!',
            cancelButtonText: 'No, cancel'
        }).then((willAdd) => {
            if (willAdd.isConfirmed) {
                var formData = new FormData();
                formData.append('venueID', venueid);
                formData.append('venue', venue);
                formData.append('address', address);
                formData.append('description', description);
                formData.append('price', price);
                formData.append('capacity', capacity);
                formData.append('venue_availability', venue_availability);
                formData.append('venue_image', venue_image);

                // Send data using POST method
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'venues.php', true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById('content').innerHTML = xhr.responseText;
                        loadPage('venues.php','content')
                    }
                };
                xhr.send(formData);
                $('.modal-backdrop').removeClass('show');
            }
        });
    } else {
        swal.fire('Error', 'Please fill all fields', 'error');
    }
}


function loadVenueForEdit(venueID) {
    // Send AJAX request to fetch venue details by venueID
    $.ajax({
        url: 'get_venue.php', // Create a new file to fetch the venue details
        type: 'GET',
        data: { venueID: venueID },
        success: function(response) {
            var venue = JSON.parse(response); // Assuming the response is JSON
            // Populate the modal form with the venue details
            $('#inputVenue').val(venue.venue);
            $('#inputAddress').val(venue.address);
            $('#inputDesc').val(venue.description);
            $('#inputPrice').val(venue.price);
            $('#inputCapacity').val(venue.capacity);
            $('#inputAvailability').val(venue.venue_availability);
            if (venue.venue_image) {
                $('#imagePreview').attr('src', venue.venue_image).show(); // Show the image preview
            } else {
                $('#imagePreview').hide(); // Hide if no image exists
            }
            $('#venueID').val(venue.venueID); // Set the venueID in the hidden field
            $('#addEditBtn').text('Update');
            $('#addVenueModal').modal('show'); // Show the modal
        },
        error: function(xhr, status, error) {
            console.log("Error fetching venue details: " + error);
        }
    });
}

function deleteVenue(venueID) {
    console.log("Deleting venue with ID:", venueID);
    // Use SweetAlert2 for confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with AJAX request if confirmed
            $.ajax({
                url: 'delete_venue.php',  // Server-side script to handle the deletion
                method: 'POST',
                data: { venueID: venueID },
                success: function(response) {
                    if (response == "success") {
                        Swal.fire(
                            'Deleted!',
                            'Your venue has been deleted.',
                            'success'
                        ); // Reload the table to reflect the deletion
                        loadPage('venues.php', 'content');
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the venue. Please try again.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the venue.',
                        'error'
                    );
                }
            });
        }
    });
}


function addEditEvent() {
    const eventID = document.getElementById('eventID').value || 0;
    const eventName = document.getElementById('inputEventName').value;
    const eventDate = document.getElementById('inputEventDate').value;
    const eventTime = document.getElementById('inputEventTime').value;
    const venueID = document.getElementById('inputVenueID').value;
    const eventDesc = document.getElementById('inputEventDesc').value;
    const eventPrice = document.getElementById('inputEventPrice').value;
    const eventCapacity = document.getElementById('inputEventCapacity').value;
    const organizer = document.getElementById('inputOrganizer').value;
    const status = document.getElementById('inputStatus').value;
    const ticket_availability = document.getElementById('inputTicketAvail').value;

    if (
        eventName !== '' &&
        eventDate !== '' &&
        eventTime !== '' &&
        venueID !== '' &&
        eventDesc !== '' &&
        eventPrice !== '' &&
        organizer !== '' &&
        status !== '' &&
        ticket_availability !== '' &&
        eventCapacity !== ''
    ) {
        Swal.fire({
            title: 'Events',
            text: `Are you sure you want to ${
                eventID == 0 ? 'add' : 'update'
            } this event?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: eventID == 0 ? 'Yes, add it!' : 'Yes, update it!',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('eventID', eventID);
                formData.append('event_name', eventName);
                formData.append('description', eventDesc);
                formData.append('date', eventDate);
                formData.append('time', eventTime);
                formData.append('venueID', venueID);
                formData.append('price', eventPrice);
                formData.append('capacity', eventCapacity);
                formData.append('organizer', organizer);
                formData.append('status', status);
                formData.append('ticket_availability', ticket_availability);

                // Send data using POST method
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'events.php', true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById('content').innerHTML = xhr.responseText;
                        loadPage('events.php', 'content');
                        Swal.fire('Success', 'Event saved successfully!', 'success');
                    }
                };
                xhr.send(formData);
                $('.modal-backdrop').removeClass('show');
            }
        });
    } else {
        Swal.fire('Error', 'Please fill all fields', 'error');
    }
}


function loadEventForEdit(eventID) {
    $.ajax({
        url: 'get_event.php', // Create this file to fetch event details
        type: 'GET',
        data: { eventID: eventID },
        success: function (response) {
            const event = JSON.parse(response);
            $('#eventID').val(event.eventID);
            $('#inputEventName').val(event.event_name);
            $('#inputEventDate').val(event.date);
            $('#inputEventTime').val(event.time);
            $('#inputVenueID').val(event.venueID);
            $('#inputEventDesc').val(event.description);
            $('#inputEventPrice').val(event.price);
            $('#inputEventCapacity').val(event.capacity);
            $('#inputOrganizer').val(event.organizer);
            $('#inputStatus').val(event.status);
            $('#inputTicketAvail').val(event.ticket_availability);

            $('#addEditEventBtn').text('Update');
            $('#addEventModal').modal('show');
        },
        error: function (xhr, status, error) {
            console.error('Error fetching event details: ' + error);
        },
    });
}



function deleteEvent(eventID) {
    // SweetAlert Confirmation for deletion
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user confirms the deletion, send the AJAX request
            $.ajax({
                url: 'delete_event.php', // Path to your PHP file
                type: 'POST',
                data: {
                    eventID: eventID
                },
                success: function(response) {
                    if (response === "success") {
                        // SweetAlert for success
                        Swal.fire({
                            icon: 'success',
                            title: 'Event Deleted!',
                            text: 'The event has been deleted successfully.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            loadPage('events.php','content')  // Reload the page after closing the SweetAlert
                        });
                    } else {
                        // SweetAlert for error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'There was an error deleting the event: ' + response,
                            confirmButtonText: 'Try Again'
                        });
                    }
                },
                error: function() {
                    // SweetAlert for AJAX failure
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: 'An error occurred while processing your request.',
                        confirmButtonText: 'Try Again'
                    });
                }
            });
        }
    });
}

function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';  // Show the image once loaded
        };
        reader.readAsDataURL(event.target.files[0]);
    }







    function addEditUser() {
    var fname = document.getElementById('fname').value;
    var mname = document.getElementById('mname').value;
    var lname = document.getElementById('lname').value;
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var email = document.getElementById('email').value;
    var role = document.getElementById('role').value;
    var birthday = document.getElementById('birthday').value;
    var contact_number = document.getElementById('contact_number').value;
    var gender = document.getElementById('gender').value;
    var userid = document.getElementById('userID').value || 0;
    console.log('Form Data:', {
    userID, fname, mname, lname, username, password, email, role, birthday, contact_number, gender
});

    if (fname !== '' && lname !== '' && username !== '' && email !== '' && 
        role !== '' && birthday !== '' && contact_number !== '' && gender !== '') {

        swal.fire({
            title: 'User',
            text: 'Are you sure you want to ' + (userid == 0 ? 'add' : 'update') + ' this user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: userid == 0 ? 'Yes, add it!' : 'Yes, update it!',
            cancelButtonText: 'No, cancel'
        }).then((willAdd) => {
            if (willAdd.isConfirmed) {
                var formData = new FormData();
                formData.append('userID', userid);
                formData.append('fname', fname);
                formData.append('mname', mname);
                formData.append('lname', lname);
                formData.append('username', username);
                formData.append('password', password);
                formData.append('email', email);
                formData.append('role', role);
                formData.append('birthday', birthday);
                formData.append('contact_number', contact_number);  // Make sure this is properly sent
                formData.append('gender', gender);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'users.php', true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById('content').innerHTML = xhr.responseText;
                        loadPage('users.php','content');
                    }
                };
                xhr.send(formData);

                $('.modal-backdrop').removeClass('show');
            }
        });
    } else {
        swal.fire('Error', 'Please fill all fields', 'error');
    }
}



function loadUserForEdit(userID) {
    // Send AJAX request to fetch venue details by userID
    $.ajax({
        url: 'get_user.php', // Create a new file to fetch the venue details
        type: 'GET',
        data: { userID: userID },
        success: function(response) {
            var users = JSON.parse(response); // Assuming the response is JSON
            // Populate the modal form with the user details
            $('#fname').val(users.fname);
            $('#mname').val(users.mname);
            $('#lname').val(users.lname);
            $('#username').val(users.username);
            $('#password').val('');
            $('#email').val(users.email);
            $('#role').val(users.role);
            $('#birthday').val(users.birthday);
            $('#contact_number').val(users.contact_number);
            $('#userID').val(users.userID); // Set the userID in the hidden field
            $('#addEditUserBtn').text('Update');
            $('#addUserModal').modal('show'); // Show the modal
        },
        error: function(xhr, status, error) {
            console.log("Error fetching user details: " + error);
        }
    });
}

function deleteUser(userID) {
    // SweetAlert Confirmation for deletion
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Prompt the user for the reason before proceeding with deletion
            Swal.fire({
                title: 'Please provide a reason for deletion',
                input: 'textarea',
                inputLabel: 'Deletion Reason',
                inputPlaceholder: 'Enter reason here...',
                inputAttributes: {
                    'aria-label': 'Enter reason for deletion'
                },
                showCancelButton: true,
                confirmButtonText: 'Delete User',
                cancelButtonText: 'Cancel'
            }).then((reasonResult) => {
                if (reasonResult.isConfirmed) {
                    // If a reason is provided, proceed with the deletion
                    $.ajax({
                        url: 'delete_user.php', // Path to your PHP file
                        type: 'POST',
                        data: {
                            userID: userID,
                            reason: reasonResult.value // Send the reason as part of the request
                        },
                        success: function(response) {
                            if (response === "success") {
                                // SweetAlert for success
                                Swal.fire({
                                    icon: 'success',
                                    title: 'User Deleted!',
                                    text: 'The user has been deleted successfully.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    loadPage('users.php','content');  // Reload the page after closing the SweetAlert
                                });
                            } else {
                                // SweetAlert for error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'There was an error deleting the user: ' + response,
                                    confirmButtonText: 'Try Again'
                                });
                            }
                        },
                        error: function() {
                            // SweetAlert for AJAX failure
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Failed',
                                text: 'An error occurred while processing your request.',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    });
                }
            });
        }
    });
}


function checkPasswordStrength() {
    const passwordField = document.getElementById('password');
    const strengthMeter = document.getElementById('passwordStrengthMeter');
    const password = passwordField.value;

    // Define password strength rules
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.length > 12) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[@$!%*?&#]/.test(password)) strength++;

    // Display strength feedback
    const levels = [
        { label: 'Very Weak', color: 'red' },
        { label: 'Weak', color: 'orange' },
        { label: 'Fair', color: 'yellow' },
        { label: 'Good', color: 'blue' },
        { label: 'Strong', color: 'green' },
    ];
    const level = levels[Math.min(strength, levels.length - 1)];
    strengthMeter.textContent = `Strength: ${level.label}`;
    strengthMeter.style.color = level.color;
}



</script>

    <script>
        const mobileScreen = window.matchMedia("(max-width: 990px )");
    $(document).ready(function () {
        $(".dashboard-nav-dropdown-toggle").click(function () {
            $(this).closest(".dashboard-nav-dropdown")
                .toggleClass("show")
                .find(".dashboard-nav-dropdown")
                .removeClass("show");
            $(this).parent()
                .siblings()
                .removeClass("show");
        });
        $(".menu-toggle").click(function () {
            if (mobileScreen.matches) {
                $(".dashboard-nav").toggleClass("mobile-show");
            } else {
                $(".dashboard").toggleClass("dashboard-compact");
            }
        });
    });
    </script>


<script type='text/javascript'>var myLink = document.querySelector('a[href="#"]');
    myLink.addEventListener('click', function(e) {
        e.preventDefault();
    });</script>
</body>
</html>