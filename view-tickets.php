<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

// Fetch user tickets
$email = $_SESSION['login'];
$query = mysqli_query($con, "SELECT * FROM ticket WHERE email_id='$email' ORDER BY posting_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>User | My Tickets</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">

<style>
body {
  background-color: #f4f6f9;
}
.page-title h3 {
  color: #003366;
  font-weight: 600;
}
.ticket-card {
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 20px 25px;
  margin-bottom: 25px;
  background: #fff;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
  transition: all 0.2s ease-in-out;
}
.ticket-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.ticket-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.ticket-header h4 {
  font-weight: 600;
  margin: 0;
  color: #003366;
}
.ticket-info strong {
  color: #003366;
}
.label-status {
  padding: 5px 10px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  color: #fff;
  text-transform: capitalize;
}
.label-status.open { background-color: #28a745; }
.label-status.closed { background-color: #dc3545; }
.label-status.pending { background-color: #ffc107; color: #000; }
.text-muted {
  color: #6c757d !important;
}
</style>
</head>

<body>
<?php include("header.php"); ?>
<div class="page-container row">
  <?php include("leftbar.php"); ?>


  <div class="page-content">
    <div class="content">
      <ul class="breadcrumb">
        <li><p>Home</p></li>
        <li><a href="#" class="active">My Tickets</a></li>
      </ul>

      <div class="page-title">
        <h3>My Created Tickets</h3>
      </div>

      <?php if (mysqli_num_rows($query) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
          <div class="ticket-card">
            <div class="ticket-header">
              <h4>Ticket #<?php echo htmlspecialchars($row['ticket_id']); ?></h4>
              <span class="label-status <?php echo strtolower($row['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
              </span>
            </div>
            <small class="text-muted">Created on <?php echo htmlspecialchars($row['posting_date']); ?></small>

            <div class="ticket-info mt-3">
              <div class="row">
                <div class="col-md-6"><strong>Full Name:</strong> <?php echo htmlspecialchars($row['name']); ?></div>
                <div class="col-md-6"><strong>ID No:</strong> <?php echo htmlspecialchars($row['id_no']); ?></div>
              </div>
              <div class="row">
                <div class="col-md-6"><strong>Phone Number:</strong> <?php echo htmlspecialchars($row['phone_number']); ?></div>
                <div class="col-md-6"><strong>Date:</strong> <?php echo htmlspecialchars($row['visit_date']); ?></div>
              </div>
              <div class="row">
                <div class="col-md-6"><strong>Reason for Visit:</strong> <?php echo htmlspecialchars($row['reason_for_visit']); ?></div>
                <div class="col-md-6"><strong>Time In:</strong> <?php echo htmlspecialchars($row['time_in']); ?></div>
              </div>
              <div class="row">
                <div class="col-md-6"><strong>Region:</strong> <?php echo htmlspecialchars($row['region']); ?></div>
                <div class="col-md-6"><strong>Time Out:</strong> <?php echo htmlspecialchars($row['time_out']); ?></div>
              </div>

              <!-- Ratings -->
              <div class="row">
                <div class="col-md-6"><strong>Adequacy of Information:</strong> <?php echo htmlspecialchars($row['info_rating']); ?></div>
                <div class="col-md-6"><strong>Ease of Process:</strong> <?php echo htmlspecialchars($row['process_rating']); ?></div>
              </div>
              <div class="row">
                <div class="col-md-6"><strong>Speed of Service:</strong> <?php echo htmlspecialchars($row['speed_rating']); ?></div>
                <div class="col-md-6"><strong>Serving Officer Remarks:</strong> <?php echo htmlspecialchars($row['officer_remarks']); ?></div>
              </div>

              <div class="row">
                <div class="col-md-12"><strong>Customer Comments:</strong> <?php echo htmlspecialchars($row['customer_comments']); ?></div>
              </div>
            </div>

            <!-- Admin Remarks -->
            <?php if (!empty($row['admin_remark'])): ?>
              <hr>
              <div>
                <strong>Admin Response:</strong> <?php echo htmlspecialchars($row['admin_remark']); ?><br>
                <small class="text-muted">Posted on <?php echo htmlspecialchars($row['admin_remark_date']); ?></small>
              </div>
            <?php endif; ?>

            <!-- Consent Information -->
            <?php if (!empty($row['consent'])): ?>
              <hr>
              <div>
                <strong>Consent:</strong> <?php echo htmlspecialchars($row['consent']); ?><br>
                <?php if ($row['consent'] === 'Accept'): ?>
                  <strong>Signature:</strong> <?php echo htmlspecialchars($row['signature']); ?><br>
                  <strong>Date:</strong> <?php echo htmlspecialchars($row['consent_date']); ?>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>

      <?php else: ?>
        <div class="alert alert-warning text-center mt-5" role="alert">
          <h5 class="mb-0">No tickets found.</h5>
          <p class="mb-0 text-muted">You haven’t created any service tickets yet.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
