<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

// Get parameters
$ticket_id = intval($_GET['ticket_id']);
$email = mysqli_real_escape_string($con, $_GET['email']);

// Fetch ticket info (fix: table name is 'ticket', not 'tickets')
$result = mysqli_query($con, "SELECT * FROM ticket WHERE ticket_id='$ticket_id' LIMIT 1");
$ticket = mysqli_fetch_assoc($result);

if (!$ticket) {
    die("Invalid ticket ID.");
}

// Generate a unique rating link
$base_url = "http://localhost/php-scrm"; // Change to your real domain if hosted online
$rating_link = $base_url . "/rate-service.php?ticket_id=" . $ticket_id . "&email=" . urlencode($email);

// Prepare email
$to = $email;
$subject = "Rate Our Service - Ticket #" . $ticket_id;
$message = "
<html>
<head><title>Rate Our Service</title></head>
<body>
<p>Dear " . htmlspecialchars($ticket['name']) . ",</p>
<p>Thank you for visiting us. We’d appreciate it if you could rate our service.</p>
<p>
Please click the link below to rate your experience:<br>
<a href='" . $rating_link . "'>Rate Now</a>
</p>
<p>Thank you,<br>Support Team</p>
</body>
</html>
";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: support@yourdomain.com\r\n"; // replace with your sender email

// Send email
if (mail($to, $subject, $message, $headers)) {
    echo "<script>alert('Rating request email sent successfully!'); window.location='view-tickets.php';</script>";
} else {
    echo "<script>alert('Failed to send email. Please try again.'); window.location='view-tickets.php';</script>";
}
?>
