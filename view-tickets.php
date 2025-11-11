<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

// Mask functions
function maskID($id) {
    $len = strlen($id);
    if ($len <= 4) return str_repeat('*', $len);
    return substr($id, 0, 2) . str_repeat('*', $len - 4) . substr($id, -2);
}

function maskPhone($phone) {
    $len = strlen($phone);
    if ($len <= 4) return str_repeat('*', $len);
    return substr($phone, 0, 2) . str_repeat('*', $len - 4) . substr($phone, -2);
}

// Fetch user tickets with optional filter
$email = $_SESSION['login'];
$filter_sql = "WHERE email_id='$email'";
$search_term = '';

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = mysqli_real_escape_string($con, trim($_GET['search']));
    $filter_sql .= " AND (ticket_id LIKE '%$search_term%' OR name LIKE '%$search_term%' OR region LIKE '%$search_term%')";
}

$query = mysqli_query($con, "SELECT * FROM ticket $filter_sql ORDER BY posting_date DESC");
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
body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
.page-title { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 20px; }
.page-title h3 { color: #003366; font-weight: 600; margin-bottom: 10px; }
.export-btn { background-color: #003366; color: #fff; font-weight: 600; border: none; border-radius: 8px; padding: 8px 16px; text-decoration: none; transition: background-color 0.2s ease-in-out; }
.export-btn:hover { background-color: #00509e; color: #fff; }
.table thead th { background-color: #003366; color: #fff; text-align: center; }
.table tbody td { vertical-align: middle; }
.label-status { padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: #fff; text-transform: capitalize; }
.label-status.open { background-color: #28a745; }
.label-status.closed { background-color: #dc3545; }
.label-status.pending { background-color: #ffc107; color: #000; }
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
       <?php if (mysqli_num_rows($query) > 0): ?>
<form method="get" action="export-my-tickets.php" target="_blank" class="d-flex align-items-center">
    <select name="format" class="form-select me-2" required>
        <option value="">Select Export Format</option>
        <option value="excel">Excel</option>
        <option value="word">Word (DOC)</option>
        <option value="pdf">PDF</option>
    </select>
    <button type="submit" class="btn btn-info">Export</button>
</form>
<?php endif; ?>

      </div>

      <!-- Search Filter -->
      <form class="mb-4" method="get" action="">
        <div class="row g-2">
          <div class="col-md-4">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" class="form-control" placeholder="Search by Ticket ID, Name, or Region">
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
          </div>
          <div class="col-md-2">
            <a href="my-tickets.php" class="btn btn-secondary w-100">Reset</a>
          </div>
        </div>
      </form>

      <?php if (mysqli_num_rows($query) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead>
            <tr>
              <th>Ticket ID</th>
              <th>Status</th>
              <th>Full Name</th>
              <th>ID No</th>
              <th>Phone Number</th>
              <th>Reason for Visit</th>
              <th>Region</th>
              <th>Visit Date</th>
              <th>Time In</th>
              <th>Time Out</th>
              <th>Ratings</th>
              <th>Officer Remarks</th>
              <th>Customer Comments</th>
              <th>Admin Remarks</th>
              <th>Consent</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['ticket_id']); ?></td>
              <td class="text-center">
                <span class="label-status <?php echo strtolower($row['status']); ?>">
                  <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo maskID($row['id_no']); ?></td>
              <td><?php echo maskPhone($row['phone_number']); ?></td>
              <td><?php echo htmlspecialchars($row['reason_for_visit']); ?></td>
              <td><?php echo htmlspecialchars($row['region']); ?></td>
              <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
              <td><?php echo htmlspecialchars($row['time_in']); ?></td>
              <td><?php echo htmlspecialchars($row['time_out']); ?></td>
              <td>
                Info: <?php echo htmlspecialchars($row['info_rating']); ?><br>
                Process: <?php echo htmlspecialchars($row['process_rating']); ?><br>
                Speed: <?php echo htmlspecialchars($row['speed_rating']); ?>
              </td>
              <td><?php echo htmlspecialchars($row['officer_remarks']); ?></td>
              <td><?php echo htmlspecialchars($row['customer_comments']); ?></td>
              <td>
                <?php if (!empty($row['admin_remark'])): ?>
                  <strong><?php echo htmlspecialchars($row['admin_remark']); ?></strong><br>
                  <small class="text-muted"><?php echo htmlspecialchars($row['admin_remark_date']); ?></small>
                <?php else: ?>
                  <em>No remarks</em>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($row['consent'])): ?>
                  <?php echo htmlspecialchars($row['consent']); ?>
                  <?php if ($row['consent'] === 'Accept'): ?>
                    <br><small>Signed: <?php echo htmlspecialchars($row['signature']); ?><br>
                    Date: <?php echo htmlspecialchars($row['consent_date']); ?></small>
                  <?php endif; ?>
                <?php else: ?>
                  <em>Not given</em>
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <?php else: ?>
        <div class="alert alert-warning text-center mt-5" role="alert">
          <h5 class="mb-0">No tickets found.</h5>
          <p class="mb-0 text-muted">You haven’t created any service tickets yet or your search returned no results.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
