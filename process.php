<?php
require_once('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// If you installed PHPMailer using Composer, use this:
require 'vendor/autoload.php';

if(isset($_POST)){
    // Capture form data
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];

    // Validate password length and requirements (same as in your code)
    // Validation code here ...

    $password = md5($password);

    // Check if email, username, or contact number already exist
    $checkUsername = "SELECT * FROM users WHERE BINARY username = '$username'";
    $checkEmail = "SELECT * FROM users WHERE BINARY email = '$email'";
    $checkContact = "SELECT * FROM users WHERE BINARY contact_number = '$contact_number'";

    $usernameResult = $conn->query($checkUsername);
    $emailResult = $conn->query($checkEmail);
    $contactResult = $conn->query($checkContact);

    if ($usernameResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username already exists!']);
    } elseif ($emailResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists!']);
    } elseif ($contactResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Contact number already exists!']);
    } else {
        $role = 'customer';
        $verification_code = md5(rand()); // Unique verification code

        // Insert query for new user with verification_code and email_verified set to 0 (unverified)
        $insertQuery = "INSERT INTO users (fname, mname, lname, username, password, email, role, birthday, contact_number, gender, verification_code, email_verified)
                        VALUES ('$fname', '$mname', '$lname', '$username', '$password', '$email', '$role', '$birthday', '$contact_number', '$gender', '$verification_code', 0)";
        if ($conn->query($insertQuery) === TRUE) {
            // Send verification email
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP(); 
                $mail->Host = 'smtp.gmail.com';  // Set your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'lextermanuel.neust@gmail.com'; // SMTP username
                $mail->Password = 'lexterlee0607'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('lextermanuel.neust@gmail.com', 'PlanWise');
                $mail->addAddress($email, $fname . ' ' . $lname); // Recipient email

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';
                $mail->Body    = "Hello $fname,<br><br>Thank you for registering with PlanWise. Please click the link below to verify your email address:<br><br>
                                  <a href='http://www.planwise.com/verify_email.php?code=$verification_code'>Verify Email</a><br><br>Thank you!<br>PlanWise Team";
                
                $mail->send();
                echo json_encode(['status' => 'success', 'message' => 'Registration successful! Please check your email for verification.']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Verification email could not be sent. Please try again.']);
            }
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
