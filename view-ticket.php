<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

$ticket_id = isset($_GET['ticket_id']) ? mysqli_real_escape_string($con, $_GET['ticket_id']) : '';
if (!$ticket_id) {
    $_SESSION['error'] = "Invalid ticket ID.";
    header("Location: my-tickets.php");
    exit;
}

$query = mysqli_query($con, "SELECT * FROM ticket WHERE ticket_id='$ticket_id'");
$ticket = mysqli_fetch_assoc($query);
if (!$ticket) {
    $_SESSION['error'] = "Ticket not found.";
    header("Location: my-tickets.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_remark = mysqli_real_escape_string($con, $_POST['admin_remark']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $update = mysqli_query($con, "UPDATE ticket SET admin_remark='$admin_remark', admin_remark_date=NOW(), status='$status' WHERE ticket_id='$ticket_id'");
    if ($update) {
        $_SESSION['success'] = "Ticket updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update ticket.";
    }

    header("Location: view-ticket.php?ticket_id=" . urlencode($ticket_id));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View/Edit Ticket <?php echo htmlspecialchars($ticket['ticket_id']); ?></title>
<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>View/Edit Ticket <?php echo htmlspecialchars($ticket['ticket_id']); ?></h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post" action="view-ticket.php?ticket_id=<?php echo urlencode($ticket_id); ?>">
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="open" <?php echo $ticket['status']=='open'?'selected':''; ?>>Open</option>
                <option value="closed" <?php echo $ticket['status']=='closed'?'selected':''; ?>>Closed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Admin Remark</label>
            <textarea name="admin_remark" class="form-control"><?php echo htmlspecialchars($ticket['admin_remark']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Update Ticket</button>

        <!-- Fixed Back Button -->
        <button type="button" class="btn btn-secondary" 
            onclick="if(document.referrer) { history.back(); } else { window.location.href='my-tickets.php'; }">
            Back
        </button>
    </form>
</div>
</body>
</html>
