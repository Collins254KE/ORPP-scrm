<?php
include("dbconnection.php");

$ticket_id = $_GET['ticket'];
$rating = $_GET['rating'] ?? null;

if ($rating) {
    mysqli_query($con, "UPDATE service_ratings SET rating='$rating' WHERE ticket_id='$ticket_id'");
    echo "<script>alert('Thank you for rating our service!'); window.location='https://yourdomain.com';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Rate Our Service</title>
<link href="assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet">
<style>
.rating {
    direction: rtl;
    unicode-bidi: bidi-override;
    text-align: center;
    font-size: 2em;
}
.rating > label {
    color: #ccc;
    cursor: pointer;
}
.rating > input:checked ~ label,
.rating > label:hover,
.rating > label:hover ~ label {
    color: gold;
}
</style>
</head>
<body class="container text-center mt-5">
    <h3>Please rate your experience</h3>
    <form method="get">
        <input type="hidden" name="ticket" value="<?= htmlspecialchars($ticket_id) ?>">
        <div class="rating">
            <input type="radio" id="star5" name="rating" value="5"/><label for="star5">★</label>
            <input type="radio" id="star4" name="rating" value="4"/><label for="star4">★</label>
            <input type="radio" id="star3" name="rating" value="3"/><label for="star3">★</label>
            <input type="radio" id="star2" name="rating" value="2"/><label for="star2">★</label>
            <input type="radio" id="star1" name="rating" value="1"/><label for="star1">★</label>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>
</html>
