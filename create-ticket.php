<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

// Handle AJAX search request
if(isset($_POST['action']) && $_POST['action']=='search'){
    $id_no  = isset($_POST['id_no']) ? trim($_POST['id_no']) : '';
    $phone  = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    
    if($id_no=='' && $phone==''){
        echo json_encode(['status'=>'error','message'=>'Provide ID No or Phone']);
        exit;
    }

    $conditions = [];
    if($id_no!='') $conditions[] = "id_no='".mysqli_real_escape_string($con,$id_no)."'";
    if($phone!='') $conditions[] = "phone_number='".mysqli_real_escape_string($con,$phone)."'";

    $where = implode(" OR ", $conditions);
    $query = "SELECT * FROM ticket WHERE $where ORDER BY posting_date DESC LIMIT 1";
    $result = mysqli_query($con,$query);

    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['status'=>'success','data'=>$row]);
    } else {
        echo json_encode(['status'=>'notfound']);
    }
    exit;
}

// Handle form submission
if (isset($_POST['send'])) {
    $email = $_SESSION['login'];

    // Collect and sanitize inputs
    $name              = trim($_POST['name']);
    $id_no             = trim($_POST['id_no']);
    $phone             = trim($_POST['phone_number']);
    $reason            = trim($_POST['reason_for_visit']);
    $visit_date        = $_POST['visit_date'];
    $time_in           = $_POST['time_in'];
    $region            = trim($_POST['region']);
    $floor             = isset($_POST['floor']) ? trim($_POST['floor']) : null;
    $department        = isset($_POST['department']) ? trim($_POST['department']) : null;
    $officer_remarks   = trim($_POST['officer_remarks']);
    $info_rating       = $_POST['info_rating'];
    $process_rating    = $_POST['process_rating'];
    $speed_rating      = $_POST['speed_rating'];
    $customer_comments = trim($_POST['customer_comments']);
    $time_out          = $_POST['time_out'];

    // Validation
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo "<script>alert('Phone number must be exactly 10 digits'); window.history.back();</script>";
        exit();
    }
    if (!preg_match('/^\d{1,10}$/', $id_no)) {
        echo "<script>alert('ID number must be numeric and up to 10 digits'); window.history.back();</script>";
        exit();
    }

    // Region prefix mapping
    $region_codes = [
        'Headquarters - Nairobi'=>'HQ','Central Region - Nyeri'=>'CEN','Coast Region - Mombasa'=>'MSA',
        'Eastern Region - Embu'=>'EST','North Eastern Region - Garissa'=>'NE','Nyanza Region - Kisumu'=>'KSM',
        'Rift Valley Region - Nakuru'=>'RV','Western Region - Kakamega'=>'WES','South Rift Region - Kericho'=>'SR',
        'Upper Eastern Region - Meru'=>'UE','Lower Eastern Region - Machakos'=>'LE','Mount Kenya Region - Nyeri'=>'MK',
        'Lake Region - Kisumu'=>'LR'
    ];
    $region_prefix = isset($region_codes[$region]) ? $region_codes[$region] : 'GEN';

    // Generate ticket ID
    $result = mysqli_query($con, "SELECT ticket_id FROM ticket WHERE ticket_id LIKE '$region_prefix-%' ORDER BY id DESC LIMIT 1");
    $last_ticket = mysqli_fetch_assoc($result);
    $new_number = $last_ticket ? str_pad(intval(end(explode("-", $last_ticket['ticket_id'])))+1,4,"0",STR_PAD_LEFT) : "0001";
    $ticket_id = $region_prefix."-".$new_number;

    $status  = "Open";
    $pdate   = date('Y-m-d');

    $query = "INSERT INTO ticket (ticket_id,email_id,status,posting_date,name,id_no,phone_number,reason_for_visit,visit_date,time_in,region,floor,department,officer_remarks,info_rating,process_rating,speed_rating,customer_comments,time_out) 
    VALUES ('$ticket_id','$email','$status','$pdate','$name','$id_no','$phone','$reason','$visit_date','$time_in','$region','$floor','$department','$officer_remarks','$info_rating','$process_rating','$speed_rating','$customer_comments','$time_out')";

    if(mysqli_query($con,$query)){
        echo "<script>alert('Ticket $ticket_id generated successfully!'); location.replace(document.referrer);</script>";
    } else {
        echo 'Error: '.mysqli_error($con);
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
<script src="assets/plugins/jquery-1.8.3.min.js"></script>
<style>
body{background:#f5f6fa;}
.form-container{width:95%;max-width:1000px;background:#fff;padding:40px 50px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);margin:30px auto;}
.form-group label{font-weight:600;color:#003366;}
.btn-primary{background:#003366;border-color:#003366;}
.page-title h3{font-weight:600;color:#003366;text-align:center;margin-bottom:30px;}
.fw-bold{font-weight:600;}
</style>
</head>
<body>
<?php include("header.php"); ?>
<div class="page-container row-fluid">
<?php include("leftbar.php"); ?>
<div class="page-content" style="min-height:100vh;">
<div class="content d-flex justify-content-center align-items-center">
<div class="form-container">
<div class="page-title"><h3>Customer Service Form</h3></div>

<form class="form-horizontal" method="post" action="">
<div class="form-group mb-3">
<label>Full Name</label>
<input type="text" name="name" class="form-control" required>
</div>
<!-- ID No with Search Button Inline -->
<div class="form-group mb-3">
    <label>ID No</label>
    <div class="input-group">
        <input type="text" name="id_no" class="form-control" maxlength="10" pattern="\d{1,10}" required>
        <button type="button" id="searchUser" class="btn btn-info">Search /Ticket</button>
    </div>
    <div id="searchResult" class="mb-3"></div>

</div>
<div class="form-group mb-3">
<label>Phone Number</label>
<input type="text" name="phone_number" class="form-control" maxlength="10" pattern="\d{10}" required>
</div>

<!-- Reason, Date, Time, Region -->
<div class="form-group mb-3">
<label>Reason for Visit</label>
<select name="reason_for_visit" class="form-control" required>
<option value="">-- Select Reason --</option>
<option value="Political Party Registration">Political Party Registration</option>
<option value="Party Compliance Inquiry">Party Compliance Inquiry</option>
<option value="Submission of Party Documents">Submission of Party Documents</option>
<option value="Party Membership Verification">Party Membership Verification</option>
<option value="Application for Symbol or Name Reservation">Application for Symbol or Name Reservation</option>
<option value="Dispute Resolution or Inquiry">Dispute Resolution or Inquiry</option>
<option value="Public Education or Outreach">Public Education or Outreach</option>
<option value="Staff or Partner Meeting">Staff or Partner Meeting</option>
<option value="General Inquiry">General Inquiry</option>
<option value="Other">Other</option>
</select>
</div>
<div class="form-group mb-3">
<label>Date</label><input type="date" name="visit_date" class="form-control" required>
</div>
<div class="form-group mb-3">
<label>Time In</label><input type="time" name="time_in" class="form-control" required>
</div>
<div class="form-group mb-3">
<label>Office / Region</label>
<select name="region" id="region" class="form-control" required>
<option value="">-- Select ORPP Office --</option>
<option value="Headquarters - Nairobi">Headquarters – Nairobi</option>
<option value="Central Region - Nyeri">Central Region – Nyeri</option>
<option value="Coast Region - Mombasa">Coast Region – Mombasa</option>
<option value="Eastern Region - Embu">Eastern Region – Embu</option>
<option value="North Eastern Region - Garissa">North Eastern Region – Garissa</option>
<option value="Nyanza Region - Kisumu">Nyanza Region – Kisumu</option>
<option value="Rift Valley Region - Nakuru">Rift Valley Region – Nakuru</option>
<option value="Western Region - Kakamega">Western Region – Kakamega</option>
<option value="South Rift Region - Kericho">South Rift Region – Kericho</option>
<option value="Upper Eastern Region - Meru">Upper Eastern Region – Meru</option>
<option value="Lower Eastern Region - Machakos">Lower Eastern Region – Machakos</option>
<option value="Mount Kenya Region - Nyeri">Mount Kenya Region – Nyeri</option>
<option value="Lake Region - Kisumu">Lake Region – Kisumu</option>
</select>
</div>
<div class="form-group mb-3" id="floorSection" style="display:none;">
<label>Floor</label>
<select name="floor" class="form-control">
<option value="">-- Select Floor --</option>
<option value="1st Floor">1st Floor</option>
<option value="2nd Floor">2nd Floor</option>
<option value="4th Floor">4th Floor</option>
</select>
</div>
<div class="form-group mb-3" id="departmentSection" style="display:none;">
<label>Department</label>
<select name="department" class="form-control">
<option value="">-- Select Department --</option>
<option value="Human Resource and Administration">Human Resource and Administration</option>
<option value="Finance and Accounts">Finance and Accounts</option>
<option value="Compliance and Political Parties Liaison">Compliance and Political Parties Liaison</option>
<option value="Political Parties Registration and Regulation">Political Parties Registration and Regulation</option>
<option value="ICT and Data Management">ICT and Data Management</option>
<option value="Legal Services">Legal Services</option>
<option value="Audit and Risk Management">Audit and Risk Management</option>
<option value="Procurement and Supplies">Procurement and Supplies</option>
<option value="Research, Policy and Strategy">Research, Policy and Strategy</option>
<option value="Public Communication and Outreach">Public Communication and Outreach</option>
<option value="Other">Other</option>
</select>
</div>
<div class="form-group mb-3">
<label>Serving Officer Remarks</label>
<textarea name="officer_remarks" class="form-control" rows="3"></textarea>
</div>

<!-- Ratings -->
<div class="form-group mb-3">
    <label class="fw-bold">How do you rate our service:</label>
    <select name="info_rating" class="form-control mb-2" required>
        <option value="">-- Adequacy of Information --</option>
        <option value="5">Excellent</option>
        <option value="4">Good</option>
        <option value="3">Fair</option>
        <option value="2">Poor</option>
    </select>
    <select name="process_rating" class="form-control mb-2" required>
        <option value="">-- Ease of Process --</option>
        <option value="5">Excellent</option>
        <option value="4">Good</option>
        <option value="3">Fair</option>
        <option value="2">Poor</option>
    </select>
    <select name="speed_rating" class="form-control" required>
        <option value="">-- Speed of Service --</option>
        <option value="5">Excellent</option>
        <option value="4">Good</option>
        <option value="3">Fair</option>
        <option value="2">Poor</option>
    </select>
</div>


<div class="form-group mb-3">
<label>Customer Comments</label>
<textarea name="customer_comments" class="form-control" rows="3"></textarea>
</div>
<div class="form-group mb-3">
<label>Time Out</label>
<input type="time" name="time_out" class="form-control">
</div>

<div class="form-group mb-3 border rounded p-3" style="background:#f8f9fa;">
<label class="fw-bold">Consent Statement</label>
<p>I hereby consent to collection & processing of personal information...</p>
<select name="consent" id="consent" class="form-control" required>
<option value="">-- Select Response --</option>
<option value="Accept">I Accept</option>
<option value="Decline">I Decline</option>
</select>
<div id="signatureSection" class="mt-3" style="display:none;">
<input type="text" name="signature" class="form-control mb-2" placeholder="Full Name as Signature">
<input type="date" name="consent_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
</div>
</div>

<div class="text-end mt-4">
<button type="reset" class="btn btn-secondary me-2">Clear Form</button>
<button type="submit" name="send" class="btn btn-primary">Submit</button>
</div>
</form>

</div>
</div>
</div>
</div>

<script>
$('#region').change(function(){
const val = $(this).val();
if(val==='Headquarters - Nairobi'){ $('#floorSection, #departmentSection').show(); }
else if(val!==''){ $('#floorSection').hide(); $('#departmentSection').show(); }
else { $('#floorSection, #departmentSection').hide(); }
});

$('#consent').change(function(){ $('#signatureSection').toggle($(this).val()==='Accept'); });

// AJAX search in same file
$('#searchUser').click(function(){
var id_no = $('input[name="id_no"]').val();
var phone = $('input[name="phone_number"]').val();
if(id_no=='' && phone==''){ alert('Enter ID No or Phone to search.'); return; }

$.post('', {action:'search', id_no:id_no, phone:phone}, function(response){
var res = JSON.parse(response);
if(res.status==='success'){
var data=res.data;
$('#searchResult').html('<div class="alert alert-info">User found. Form auto-filled.</div>');
$('input[name="name"]').val(data.name);
$('input[name="id_no"]').val(data.id_no);
$('input[name="phone_number"]').val(data.phone_number);
$('select[name="reason_for_visit"]').val(data.reason_for_visit);
$('input[name="visit_date"]').val(data.visit_date);
$('input[name="time_in"]').val(data.time_in);
$('select[name="region"]').val(data.region).trigger('change');
$('select[name="floor"]').val(data.floor);
$('select[name="department"]').val(data.department);
$('textarea[name="officer_remarks"]').val(data.officer_remarks);
$('select[name="info_rating"]').val(data.info_rating);
$('select[name="process_rating"]').val(data.process_rating);
$('select[name="speed_rating"]').val(data.speed_rating);
$('textarea[name="customer_comments"]').val(data.customer_comments);
$('input[name="time_out"]').val(data.time_out);
} else if(res.status==='notfound'){
$('#searchResult').html('<div class="alert alert-success">No existing user found. You can proceed.</div>');
} else { alert(res.message); }
});
});
</script>

<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
