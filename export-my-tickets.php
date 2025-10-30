<?php
session_start();
include("dbconnection.php");

// ✅ Check if session is active
if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    die("Your session has expired. Please log in again.");
}

$email = $_SESSION['login'];

// ✅ Fetch user tickets
$query = mysqli_query($con, "SELECT * FROM ticket WHERE email_id='" . mysqli_real_escape_string($con, $email) . "' ORDER BY posting_date DESC");

// ✅ If no data found
if (mysqli_num_rows($query) == 0) {
    die("No tickets found for export.");
}

// ✅ Set headers for Excel export
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=my_tickets_" . date("Y-m-d_H-i-s") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ Start the table
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
  <th>Adequacy of Information</th>
  <th>Ease of Process</th>
  <th>Speed of Service</th>
  <th>Officer Remarks</th>
  <th>Customer Comments</th>
  <th>Status</th>
  <th>Admin Remark</th>
  <th>Admin Remark Date</th>
  <th>Consent</th>
  <th>Signature</th>
  <th>Consent Date</th>
  <th>Posting Date</th>
</tr>
</thead>
<tbody>";

// ✅ Loop through and output each record
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
    echo "<td>" . htmlspecialchars($row['info_rating']) . "</td>";
    echo "<td>" . htmlspecialchars($row['process_rating']) . "</td>";
    echo "<td>" . htmlspecialchars($row['speed_rating']) . "</td>";
    echo "<td>" . htmlspecialchars($row['officer_remarks']) . "</td>";
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
