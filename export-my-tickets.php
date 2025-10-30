<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

// Only export tickets for the logged-in user
$email = $_SESSION['login'];

// Set headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=my_tickets_export_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Table header
echo "<table border='1'>";
echo "<thead>
<tr>
  <th>Ticket ID</th>
  <th>Full Name</th>
  <th>ID No</th>
  <th>Phone Number</th>
  <th>Reason for Visit</th>
  <th>Date</th>
  <th>Time In</th>
  <th>Time Out</th>
  <th>Region</th>
  <th>Officer Remarks</th>
  <th>Adequacy of Information</th>
  <th>Ease of Process</th>
  <th>Speed of Service</th>
  <th>Customer Comments</th>
  <th>Status</th>
  <th>Admin Remark</th>
  <th>Admin Remark Date</th>
  <th>Consent</th>
  <th>Signature</th>
  <th>Consent Date</th>
  <th>Created At</th>
</tr>
</thead><tbody>";

// Fetch and output user-specific tickets
$query = mysqli_query($con, "SELECT * FROM ticket WHERE email_id='$email' ORDER BY posting_date DESC");
while ($row = mysqli_fetch_assoc($query)) {
  echo "<tr>";
  echo "<td>" . htmlspecialchars($row['ticket_id']) . "</td>";
  echo "<td>" . htmlspecialchars($row['name']) . "</td>";
  echo "<td>" . htmlspecialchars($row['id_no']) . "</td>";
  echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
  echo "<td>" . htmlspecialchars($row['reason_for_visit']) . "</td>";
  echo "<td>" . htmlspecialchars($row['visit_date']) . "</td>";
  echo "<td>" . htmlspecialchars($row['time_in']) . "</td>";
  echo "<td>" . htmlspecialchars($row['time_out']) . "</td>";
  echo "<td>" . htmlspecialchars($row['region']) . "</td>";
  echo "<td>" . htmlspecialchars($row['officer_remarks']) . "</td>";
  echo "<td>" . htmlspecialchars($row['info_rating']) . "</td>";
  echo "<td>" . htmlspecialchars($row['process_rating']) . "</td>";
  echo "<td>" . htmlspecialchars($row['speed_rating']) . "</td>";
  echo "<td>" . htmlspecialchars($row['customer_comments']) . "</td>";
  echo "<td>" . htmlspecialchars($row['status']) . "</td>";
  echo "<td>" . htmlspecialchars($row['admin_remark']) . "</td>";
  echo "<td>" . htmlspecialchars($row['admin_remark_date']) . "</td>";
  echo "<td>" . htmlspecialchars($row['consent']) . "</td>";
  echo "<td>" . htmlspecialchars($row['signature']) . "</td>";
  echo "<td>" . htmlspecialchars($row['consent_date']) . "</td>";
  echo "<td>" . htmlspecialchars($row['posting_date']) . "</td>";
  echo "</tr>";
}

echo "</tbody></table>";
exit;
?>
