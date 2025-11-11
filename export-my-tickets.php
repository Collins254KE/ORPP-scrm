<?php
session_start();
include("dbconnection.php");

// Validate session
$email = $_SESSION['login'] ?? die("Your session has expired. Please log in again.");
$format = $_GET['format'] ?? 'excel';

// Optional search filter
$search_term = trim($_GET['search'] ?? '');
$filter_sql = "WHERE email_id='" . mysqli_real_escape_string($con, $email) . "'";
if ($search_term !== '') {
    $filter_sql .= " AND (ticket_id LIKE '%$search_term%' OR name LIKE '%$search_term%' OR region LIKE '%$search_term%')";
}

// Fetch ticket data
$result = mysqli_query($con, "SELECT * FROM ticket $filter_sql ORDER BY posting_date DESC");
$tickets = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($tickets)) die("No tickets found for export.");

// ----------------- EXCEL EXPORT (UNCHANGED) -----------------
if ($format == 'excel') {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=my_tickets_" . date("Y-m-d_H-i-s") . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<thead><tr>
        <th>Ticket ID</th><th>Full Name</th><th>ID No</th><th>Phone Number</th><th>Reason for Visit</th>
        <th>Date</th><th>Time In</th><th>Time Out</th><th>Region</th>
        <th>Info Rating</th><th>Process Rating</th><th>Speed Rating</th><th>Officer Remarks</th>
        <th>Customer Comments</th><th>Status</th><th>Admin Remark</th><th>Consent</th><th>Signature</th><th>Consent Date</th><th>Posting Date</th>
    </tr></thead><tbody>";

    foreach ($tickets as $row) {
        $id_no = strlen($row['id_no'])>4 ? substr($row['id_no'],0,2).'****'.substr($row['id_no'],-2) : $row['id_no'];
        $phone = strlen($row['phone_number'])>4 ? substr($row['phone_number'],0,2).'****'.substr($row['phone_number'],-2) : $row['phone_number'];
        echo "<tr>
            <td>{$row['ticket_id']}</td>
            <td>{$row['name']}</td>
            <td>$id_no</td>
            <td>$phone</td>
            <td>{$row['reason_for_visit']}</td>
            <td>{$row['visit_date']}</td>
            <td>{$row['time_in']}</td>
            <td>{$row['time_out']}</td>
            <td>{$row['region']}</td>
            <td>{$row['info_rating']}</td>
            <td>{$row['process_rating']}</td>
            <td>{$row['speed_rating']}</td>
            <td>{$row['officer_remarks']}</td>
            <td>{$row['customer_comments']}</td>
            <td>{$row['status']}</td>
            <td>{$row['admin_remark']}</td>
            <td>{$row['consent']}</td>
            <td>{$row['signature']}</td>
            <td>{$row['consent_date']}</td>
            <td>{$row['posting_date']}</td>
        </tr>";
    }
    echo "</tbody></table>";
    exit;
}

// ----------------- WORD & PDF EXPORT -----------------

// Compute dynamic summary
$total_customers = count($tickets);
$customers_rated = 0;
$rating_counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$total_rating_sum = 0;
$total_rating_entries = 0;

$observations = [];

foreach ($tickets as $t) {
    $ratings = [
        (int)($t['info_rating'] ?? 0),
        (int)($t['process_rating'] ?? 0),
        (int)($t['speed_rating'] ?? 0)
    ];
    $ratings = array_filter($ratings);
    if (!empty($ratings)) $customers_rated++;

    foreach ($ratings as $r) {
        if ($r >= 1 && $r <= 5) {
            $rating_counts[$r]++;
            $total_rating_sum += $r;
            $total_rating_entries++;
        }
    }

    // Observations based on officer remarks
    $remarks = strtolower($t['officer_remarks'] ?? '');
    if (strpos($remarks, 'fieldwork') !== false) $observations['fieldwork'] = "Some of the serving officers were in the field for fieldwork and training ({$t['visit_date']}).";
    if (strpos($remarks, 'explain') !== false) $observations['clarify'] = "Serving officers should clearly explain to clients what is needed for them to be served ({$t['visit_date']}).";
    if (!empty($t['speed_rating']) && $t['speed_rating'] >= 4) $observations['speed'] = "Clients are happy with the speed at which they are being served ({$t['visit_date']}).";
}

$rating_percentage = $total_customers > 0 ? round(($customers_rated / $total_customers) * 100) : 0;
$average_rating = $total_rating_entries > 0 ? round($total_rating_sum / $total_rating_entries, 1) : 0;

// Build dynamic tables
$ratings_table = '<table>
<tr><th>Rating</th><th>1 (Poor)</th><th>2 (Fair)</th><th>3 (Fair)</th><th>4 (Good)</th><th>5 (Excellent)</th><th>Average</th></tr>
<tr>
<td>Count</td>
<td>' . $rating_counts[1] . '</td>
<td>' . $rating_counts[2] . '</td>
<td>' . $rating_counts[3] . '</td>
<td>' . $rating_counts[4] . '</td>
<td>' . $rating_counts[5] . '</td>
<td>' . $average_rating . '</td>
</tr>
</table>';

$observations_html = '<ul>';
foreach ($observations as $obs) $observations_html .= '<li>' . $obs . '</li>';
$observations_html .= '</ul>';

$recommendations = ["Continue encouraging customers to provide feedback."];
if (isset($observations['clarify'])) $recommendations[] = "Enhance staff training to clarify requirements based on feedback patterns.";
if (isset($observations['fieldwork'])) $recommendations[] = "Implement action plans to manage fieldwork without affecting service delivery.";
$recommendations_html = '<ul>';
foreach ($recommendations as $rec) $recommendations_html .= '<li>' . $rec . '</li>';
$recommendations_html .= '</ul>';

// Build report HTML
$report = '
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; line-height: 1.5; margin: 40px; }
h1, h2 { color: #003366; }
h1 { text-align: center; text-transform: uppercase; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
th, td { border: 1px solid #555; padding: 8px; text-align: left; }
th { background-color: #003366; color: #fff; }
ul { margin: 0; padding-left: 20px; }
p { text-align: justify; }
</style>
</head>
<body>

<h1>CUSTOMER SERVICE REPORT</h1>
<p><strong>VENUE:</strong> ORPP Headquarters, 4th Floor<br>
<strong>DATE:</strong> 2025-11-08 to 2025-11-11</p>

<h2>1.0 INTRODUCTION</h2>
<p>The Office of the Registrar of Political Parties (ORPP) values an institutionalized positive customer
experience in undertaking all its processes. Through the customer service personnel, necessary customer
service reports are developed and maintained as a way of managing customer records and data as well as
meaningfully utilizing feedback received to improve service delivery.</p>

<h2>2.0 CUSTOMER DETAILS</h2>
<table>
<thead>
<tr>
<th>S/No</th><th>Ticket No</th><th>Date</th><th>Name</th><th>ID No</th><th>Contact</th><th>Reason</th><th>Status</th>
</tr>
</thead>
<tbody>';

$sn = 1; // Initialize serial number

foreach ($tickets as $row) {
    $maskedID = strlen($row['id_no']) > 4 
        ? substr($row['id_no'], 0, 2) . '****' . substr($row['id_no'], -2) 
        : $row['id_no'];
        
    $maskedPhone = strlen($row['phone_number']) > 4 
        ? substr($row['phone_number'], 0, 2) . 'XXXX' . substr($row['phone_number'], -2) 
        : $row['phone_number'];

    $report .= "<tr>
        <td>" . $sn++ . "</td> <!-- Auto increment S/No -->
        <td>{$row['ticket_id']}</td>
        <td>{$row['visit_date']}</td>
        <td>{$row['name']}</td>
        <td>$maskedID</td>
        <td>$maskedPhone</td>
        <td>{$row['reason_for_visit']}</td>
        <td>{$row['status']}</td>
    </tr>";
}
$report .= '</tbody></table>

<h2>3.0 SUMMARY OF CUSTOMERS ATTENDED</h2>
<p>In the period under review, there were <strong>' . $total_customers . '</strong> customers served on one-on-one basis.</p>
<p>Out of these, <strong>' . $rating_percentage . '%</strong> rated the quality of our services with an average rating of <strong>' . $average_rating . ' out of 5</strong>.</p>
' . $ratings_table . '

<h2>4.0 OBSERVATIONS</h2>
' . $observations_html . '

<h2>5.0 RECOMMENDATIONS</h2>
' . $recommendations_html . '

<h2>6.0 CONCLUSION</h2>
<p>The efforts through all the departments to institutionalize customer experience positively impact ORPP\'s service delivery.</p>

<br><br>
<p><strong>Compiled by:</strong><br>
Name: Brenda Kamau<br>
Date: ' . date("Y-m-d") . '<br>
Signature: ....................................................</p>
</body></html>';

// ----------------- EXPORT -----------------

if ($format == 'word') {
    header("Content-Type: application/vnd.ms-word");
    header("Content-Disposition: attachment; filename=customer_service_report_" . date("Y-m-d_H-i-s") . ".doc");
    echo $report;
    exit;
}

if ($format == 'pdf') {
    require_once __DIR__ . '/vendor/autoload.php'; // load mPDF

    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 20,
        'margin_bottom' => 20,
    ]);

    $mpdf->SetTitle('Customer Service Report');
    $mpdf->WriteHTML($report); // convert HTML to PDF
    $mpdf->Output('customer_service_report_' . date("Y-m-d_H-i-s") . '.pdf', 'D'); // 'D' = download
    exit;
}


?>
