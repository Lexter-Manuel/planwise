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

// Fetch all admin and organizer users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$archivedSql = "SELECT * FROM archived_users";
$archivedResult = $conn->query($archivedSql);


$userID = isset($_GET['userID']) ? $_GET['userID'] : 0;

// Handle Add/Edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userID']) && 
    isset($_POST['fname']) && 
    isset($_POST['mname']) && 
    isset($_POST['lname']) && 
    isset($_POST['username']) && 
    isset($_POST['password']) && 
    isset($_POST['email']) && 
    isset($_POST['role']) && 
    isset($_POST['birthday']) && 
    isset($_POST['contact_number']) && 
    isset($_POST['gender'])) {

        $userid = $_POST['userID'];
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $mname = mysqli_real_escape_string($conn, $_POST['mname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
        $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);

        if (strlen($password) < 8 || strlen($password) > 20) {
            echo json_encode(['status' => 'error', 'message' => 'Password must be between 8 and 20 characters.']);
            exit();
        }
        if (!preg_match('@[A-Z]@', $password)) {
            echo json_encode(['status' => 'error', 'message' => 'Password must include at least one uppercase letter.']);
            exit();
        }
        if (!preg_match('@[a-z]@', $password)) {
            echo json_encode(['status' => 'error', 'message' => 'Password must include at least one lowercase letter.']);
            exit();
        }
        if (!preg_match('@[0-9]@', $password)) {
            echo json_encode(['status' => 'error', 'message' => 'Password must include at least one number.']);
            exit();
        }
        if (!preg_match('@[\W_]@', $password)) {
            echo json_encode(['status' => 'error', 'message' => 'Password must include at least one special character.']);
            exit();
        }
        // MD5 Hashing (for educational or legacy purposes)
        $hashed_password = ($password !== '') ? md5($password) : null;

        if ($usernameResult->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username already exists!']);
        } elseif ($emailResult->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already exists!']);
        } elseif ($contactResult->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Contact number already exists!']);
        } else {
            if ($userid == 0) {
                // Insert new user
                $sql = "INSERT INTO users (fname, mname, lname, username, password, email, role, birthday, contact_number, gender)
                        VALUES ('$fname', '$mname', '$lname', '$username', '$hashed_password', '$email', '$role', '$birthday', '$contact_number', '$gender')";
            } else {
                // Update existing user
                $hashed_password = $password !== '' ? "password = '" . md5($password) . "'," : '';
                $sql = "UPDATE users 
                        SET fname = '$fname', mname = '$mname', lname = '$lname', username = '$username', $hashed_password 
                            email = '$email', role = '$role', birthday = '$birthday', contact_number = '$contact_number', gender = '$gender'
                        WHERE userID = '$userid'";
            }
        }

        if (mysqli_query($conn, $sql)) {
            echo "User saved successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Missing parameters.";
    }
}
?>

<link rel="stylesheet" href="style/style.css">

<div class="container">
    <h1 class="text-center">Users List</h1>

    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
    </div>

    <table id="usersTable" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Birthday</th>
                <th>Contact Number</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['userID']; ?></td>
                <td><?php echo $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['birthday']; ?></td>
                <td><?php echo $row['contact_number']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="loadUserForEdit(<?php echo $row['userID']; ?>)">Edit</button>
                    <button type="button" class="btn btn-danger" onclick="deleteUser(<?php echo $row['userID']; ?>)">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<div class="container">
    <h1 class="text-center">Archived Users</h1>
    <table id="archivedUsersTable" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Archived At</th>
                <th>Archived By</th>
                <th>Birthday</th>
                <th>Contact Number</th>
                <th>Gender</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $archivedResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['archivedUserID']; ?></td>
                <td><?php echo $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['archived_at']; ?></td>
                <td><?php echo $row['archived_by']; ?></td>
                <td><?php echo $row['birthday']; ?></td>
                <td><?php echo $row['contact_number']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['reason']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<!-- Add/Edit Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" hidden id="userID" name="userID" value="<?php echo $userID ? $userID : 0; ?>">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" required>
                    </div>
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" class="form-control" id="mname" name="mname">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            minlength="8" 
                            maxlength="20" 
                            placeholder="Enter password (8-20 characters)" 
                            oninput="checkPasswordStrength()" 
                        >
                        <small id="passwordHelp" class="form-text text-muted">
                            Password must be 8-20 characters and include uppercase, lowercase, number, and a special character.
                        </small>
                        <div id="passwordStrengthMeter"></div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email" name="email" oninput="validateGmail()" required>
                        <small id="emailError" class="form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="organizer">Organizer</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Birthday</label>
                        <input type="date" class="form-control" id="birthday" name="birthday">
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="addEditUserBtn" class="btn btn-primary" onclick="addEditUser()">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.getElementById('email').addEventListener('input', validateEmail);

function validateEmail() {
    const emailField = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const emailValue = emailField.value;

    // Simple regex for email validation
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (emailPattern.test(emailValue)) {
        emailError.textContent = ''; // Clear error message
        emailField.classList.remove('is-invalid');
    } else {
        emailError.textContent = 'Please enter a valid email address.';
        emailField.classList.add('is-invalid');
    }
}

</script>