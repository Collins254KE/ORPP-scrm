<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = mysqli_real_escape_string($con, $_POST['ticket_id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $allowed_statuses = ['closed']; // Only allow closing
    if (!in_array($status, $allowed_statuses)) {
        $_SESSION['error'] = "Invalid status.";
        header("Location: my-tickets.php");
        exit;
    }

    $query = "UPDATE ticket SET status='$status' WHERE ticket_id='$ticket_id'";
    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Ticket status updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update ticket status.";
    }

    header("Location: my-tickets.php");
    exit;
}
?>
