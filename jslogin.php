<?php
require_once('config.php');

// Get the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];
$password = md5($password);

// Use BINARY to make the login check case-sensitive
$sql = "SELECT * FROM users WHERE BINARY username = '$username' AND BINARY password = '$password'";
$result = $conn->query($sql);

if($result->num_rows > 0){
    session_start();
    $row = $result->fetch_assoc();
    
    // Check if the email is verified
    if ($row['email_verified'] == 0) {
        echo json_encode(['status'=>'error', 'message'=>'Please verify your email before logging in.']);
        exit(); // Stop execution here if email is not verified
    }
    
    $_SESSION['userID'] = $row['userID'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];  // Save the role in session
    
    // Check the user's role and redirect accordingly
    if($row['role'] == 'customer'){
        echo json_encode(['status'=>'success', 'message'=>'customer', 'redirect_url'=>'customer/homepage.php']);  // Redirect to customer homepage
    } else {
        echo json_encode(['status'=>'success', 'message'=>'admin/organizer', 'redirect_url'=>'admin/admin.php']);  // Redirect to admin homepage
    }
    exit();  // Ensure no further code is executed after the redirect
} else {
    echo json_encode(['status'=>'error', 'message'=>'Failed to log in. Please check your username and password.']);
}
?>
