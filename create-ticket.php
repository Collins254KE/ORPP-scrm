<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

if (isset($_POST['send'])) {
    // Generate unique ticket ID
    $count_my_page = "hitcounter.txt";
    $hits = file($count_my_page);
    $hits[0]++;
    file_put_contents($count_my_page, $hits[0]);
    $tid = $hits[0];

    $email = $_SESSION['login'];

    // Collect and sanitize inputs
    $name              = trim($_POST['name']);
    $id_no             = trim($_POST['id_no']);
    $phone             = trim($_POST['phone_number']);
    $reason            = trim($_POST['reason_for_visit']);
    $visit_date        = $_POST['visit_date'];
    $time_in           = $_POST['time_in'];
    $region            = trim($_POST['region']);
    $officer_remarks   = trim($_POST['officer_remarks']);
    $info_rating       = $_POST['info_rating'];
    $process_rating    = $_POST['process_rating'];
    $speed_rating      = $_POST['speed_rating'];
    $customer_comments = trim($_POST['customer_comments']);
    $time_out          = $_POST['time_out'];

    // Validation (Server-side)
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo "<script>alert('Phone number must be exactly 10 digits'); window.history.back();</script>";
        exit();
    }

    if (!preg_match('/^\d{1,10}$/', $id_no)) {
        echo "<script>alert('ID number must be numeric and up to 10 digits'); window.history.back();</script>";
        exit();
    }

    // Other info
    $status = "Open";
    $pdate  = date('Y-m-d');

    // Insert into database
    $query = "
        INSERT INTO ticket (
            ticket_id, email_id, status, posting_date,
            name, id_no, phone_number, reason_for_visit, visit_date,
            time_in, region, officer_remarks,
            info_rating, process_rating, speed_rating,
            customer_comments, time_out
        ) VALUES (
            '$tid', '$email', '$status', '$pdate',
            '$name', '$id_no', '$phone', '$reason', '$visit_date',
            '$time_in', '$region', '$officer_remarks',
            '$info_rating', '$process_rating', '$speed_rating',
            '$customer_comments', '$time_out'
        )
    ";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Ticket Generated Successfully'); location.replace(document.referrer);</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRM | Customer Service Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f6fa;
        }

        .form-container {
            width: 95%;
            max-width: 1000px;
            background: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
        }

        .form-group label {
            font-weight: 600;
            color: #003366;
        }

        .btn-primary {
            background-color: #003366;
            border-color: #003366;
        }

        .page-title h3 {
            font-weight: 600;
            color: #003366;
            text-align: center;
            margin-bottom: 30px;
        }

        .fw-bold {
            font-weight: 600;
        }
    </style>
</head>

<body>
<?php include("header.php"); ?>
<div class="page-container row-fluid">
    <?php include("leftbar.php"); ?>

    <div class="page-content" style="min-height: 100vh;">
        <div class="content d-flex justify-content-center align-items-center">
            <div class="form-container">
                <div class="page-title">
                    <h3>Customer Service Form</h3>
                </div>
<form class="form-horizontal" method="post" action="">
    <!-- Full Name -->
    <div class="form-group mb-3">
        <label>Full Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <!-- ID No -->
    <div class="form-group mb-3">
        <label>ID No</label>
        <input type="text" name="id_no" class="form-control" maxlength="10" pattern="\d{1,10}"
               title="Enter up to 10 numeric digits" required>
    </div>

    <!-- Phone Number -->
    <div class="form-group mb-3">
        <label>Phone Number</label>
        <input type="text" name="phone_number" class="form-control" maxlength="10" pattern="\d{10}"
               title="Phone number must be exactly 10 digits" required>
    </div>

    <!-- Reason for Visit -->
    <div class="form-group mb-3">
        <label>Reason for Visit</label>
        <input type="text" name="reason_for_visit" class="form-control">
    </div>

    <!-- Date -->
    <div class="form-group mb-3">
        <label>Date</label>
        <input type="date" name="visit_date" class="form-control" required>
    </div>

    <!-- Time In -->
    <div class="form-group mb-3">
        <label>Time In</label>
        <input type="time" name="time_in" class="form-control" required>
    </div>

    <!-- Region -->
    <div class="form-group mb-3">
        <label>Region</label>
        <input type="text" name="region" class="form-control">
    </div>

    <!-- Officer Remarks -->
    <div class="form-group mb-3">
        <label>Serving Officer Remarks</label>
        <textarea name="officer_remarks" class="form-control" rows="3"></textarea>
    </div>

    <!-- Ratings -->
    <div class="form-group mb-3">
        <label class="fw-bold">How do you rate our service with respect to:</label>

        <div class="mt-2">
            <label for="info_rating">Adequacy of Information</label>
            <select name="info_rating" id="info_rating" class="form-control mb-2" required>
                <option value="">-- Select Rating --</option>
                <option value="Excellent">Excellent</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
            </select>
        </div>

        <div class="mt-2">
            <label for="process_rating">Ease of Process</label>
            <select name="process_rating" id="process_rating" class="form-control mb-2" required>
                <option value="">-- Select Rating --</option>
                <option value="Excellent">Excellent</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
            </select>
        </div>

        <div class="mt-2">
            <label for="speed_rating">Speed of Service</label>
            <select name="speed_rating" id="speed_rating" class="form-control" required>
                <option value="">-- Select Rating --</option>
                <option value="Excellent">Excellent</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
            </select>
        </div>
    </div>

    <!-- Customer Comments -->
    <div class="form-group mb-3">
        <label>Customer Comments</label>
        <textarea name="customer_comments" class="form-control" rows="3"
                  placeholder="Your feedback helps us improve our service..."></textarea>
    </div>

    <!-- Time Out -->
    <div class="form-group mb-3">
        <label>Time Out</label>
        <input type="time" name="time_out" class="form-control">
    </div>

    <!-- Consent Statement -->
    <div class="form-group mb-3 border rounded p-3" style="background:#f8f9fa;">
        <label class="fw-bold">Consent Statement</label>
        <p class="mt-2" style="text-align:justify; line-height:1.5;">
            I hereby consent to the collection and processing of my personal information by the Office of the Registrar of Political Parties (ORPP)
            for official use to improve our service delivery measures including obtaining feedback and maintenance of customer records in accordance
            with the Data Protection Act (Cap 411C). The customer information will be treated with utmost confidentiality and used solely for the stated purpose.
        </p>

        <div class="mt-2">
            <label>Do you accept or decline?</label>
            <select name="consent" id="consent" class="form-control" required>
                <option value="">-- Select Response --</option>
                <option value="Accept">I Accept</option>
                <option value="Decline">I Decline</option>
            </select>
        </div>

        <div id="signatureSection" class="mt-3" style="display:none;">
            <div class="form-group mb-2">
                <label>Signature</label>
                <input type="text" name="signature" class="form-control" placeholder="Enter your full name as signature">
            </div>

            <div class="form-group mb-2">
                <label>Date</label>
                <input type="date" name="consent_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="text-end mt-4">
        <button type="reset" class="btn btn-secondary me-2">Clear Form</button>
        <button type="submit" name="send" class="btn btn-primary">Submit</button>
    </div>
</form>

<!-- JS to toggle signature/date visibility -->
<script>
document.getElementById('consent').addEventListener('change', function() {
    const sigSection = document.getElementById('signatureSection');
    sigSection.style.display = (this.value === 'Accept') ? 'block' : 'none';
});
</script>


                   
            </div>
        </div>
    </div>
</div>

<script src="assets/plugins/jquery-1.8.3.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
