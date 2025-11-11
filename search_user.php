<?php
session_start();
include("dbconnection.php");

$id_no  = isset($_POST['id_no']) ? trim($_POST['id_no']) : '';
$phone  = isset($_POST['phone']) ? trim($_POST['phone']) : '';

if ($id_no == '' && $phone == '') {
    echo json_encode(['status'=>'error', 'message'=>'Provide ID No or Phone']);
    exit;
}

$conditions = [];
if ($id_no != '') $conditions[] = "id_no='".mysqli_real_escape_string($con, $id_no)."'";
if ($phone != '') $conditions[] = "phone_number='".mysqli_real_escape_string($con, $phone)."'";

$where = implode(" OR ", $conditions);

$query = "SELECT * FROM ticket WHERE $where ORDER BY posting_date DESC LIMIT 1";
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['status'=>'success', 'data'=>$row]);
} else {
    echo json_encode(['status'=>'notfound']);
}
?>
