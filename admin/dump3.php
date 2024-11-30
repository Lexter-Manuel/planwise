<?php
$to = "morningstar.demiurgos06@gmail.com";
$subject = "Test Email from XAMPP";
$message = "This is a test email sent from XAMPP.";
$headers = "From: lextermanuel.neust@gmail.com";

if(mail($to, $subject, $message, $headers)){
    echo "Mail sent successfully!";
} else {
    echo "Failed to send mail.";
}
?>