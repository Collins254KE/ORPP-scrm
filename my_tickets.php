<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

// Handle search/filter
$search_term = '';
if (isset($_GET['search'])) {
    $search_term = trim($_GET['search']);
}

// Fetch user tickets with optional search
$email = $_SESSION['login'];
$filter_sql = "WHERE email_id='$email'";
if (!empty($search_term)) {
    $search_sql = mysqli_real_escape_string($con, $search_term);
    $filter_sql .= " AND (ticket_id LIKE '%$search_sql%' OR name LIKE '%$search_sql%' OR region LIKE '%$search_sql%')";
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
body { background-color: #f4f6f9; }
.page-title h3 { color: #003366; font-weight: 600; }
.export-btn { float: right; background-color: #003366; color: #fff; font-weight: 600; border: none; border-radius: 8px; padding: 8px 16px; text-decoration: none; transition: background-color 0.2s; }
.export-btn:hover { background-color: #00509e; color: #fff; }
.table th, .table td { vertical-align: middle; }
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

      <div class="page-title d-flex justify-content-between align-items-center">
        <h3>My Created Tickets</h3>
        <?php if (mysqli_num_rows($query) > 0): ?>
            <a href="export_my_tickets.php<?php echo !empty($search_term) ? '?search='.urlencode($search_term) : ''; ?>" class="export-btn">
                <i class="bi bi-file-earmark-excel"></i> Export to Excel
            </a>
        <?php endif; ?>
      </div>

      <!-- Search Form -->
      <form class="row g-3 my-3" method="get" action="">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by ID, Name or Region" value="<?php echo htmlspecialchars($search_term); ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
      </form>

      <?php if (mysqli_num_rows($query) > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-primary">
              <tr>
                <th>Ticket ID</th>
                <th>Status</th>
                <th>Full Name</th>
                <th>ID No</th>
                <th>Phone Number</th>
                <th>Reason</th>
                <th>Region</th>
                <th>Visit Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Info Rating</th>
                <th>Process Rating</th>
                <th>Speed Rating</th>
                <th>Officer Remarks</th>
                <th>Customer Comments</th>
                <th>Admin Remark</th>
                <th>Consent</th>
              </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)): 
                // Mask ID & Phone
                $id_no = strlen($row['id_no']) > 4 ? substr($row['id_no'],0,2)."****".substr($row['id_no'],-2) : $row['id_no'];
                $phone = strlen($row['phone_number']) > 4 ? substr($row['phone_number'],0,2)."****".substr($row['phone_number'],-2) : $row['phone_number'];
            ?>
              <tr>
                <td><?php echo htmlspecialchars($row['ticket_id']); ?></td>
                <td><span class="label-status <?php echo strtolower($row['status']); ?>"><?php echo ucfirst(htmlspecialchars($row['status'])); ?></span></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo $id_no; ?></td>
                <td><?php echo $phone; ?></td>
                <td><?php echo htmlspecialchars($row['reason_for_visit']); ?></td>
                <td><?php echo htmlspecialchars($row['region']); ?></td>
                <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                <td><?php echo htmlspecialchars($row['time_out']); ?></td>
                <td><?php echo htmlspecialchars($row['info_rating']); ?></td>
                <td><?php echo htmlspecialchars($row['process_rating']); ?></td>
                <td><?php echo htmlspecialchars($row['speed_rating']); ?></td>
                <td><?php echo htmlspecialchars($row['officer_remarks']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_comments']); ?></td>
                <td><?php echo htmlspecialchars($row['admin_remark']); ?></td>
                <td>
                    <?php echo htmlspecialchars($row['consent']); ?>
                    <?php if($row['consent'] === 'Accept'): ?>
                        <br><strong>Signature:</strong> <?php echo htmlspecialchars($row['signature']); ?>
                        <br><strong>Date:</strong> <?php echo htmlspecialchars($row['consent_date']); ?>
                    <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-warning text-center mt-5">
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
