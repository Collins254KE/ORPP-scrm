<?php
session_start();
include("dbconnection.php");
include("checklogin.php");
check_login();

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $aemail = $_POST['alt_email'];
    $mobile = $_POST['phone'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $a = mysqli_query($con, "UPDATE user SET name='$name', mobile='$mobile', gender='$gender', alt_email='$aemail', address='$address' WHERE email='" . $_SESSION['login'] . "'");
    if ($a) {
        echo "<script>alert('Your profile updated successfully.');location.replace(document.referrer)</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ORPP | User Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap & Fonts -->
<link href="assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Catamaran:wght@700;900&display=swap" rel="stylesheet">
<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">

<style>
body {
  background-color: #f4f6f9;
  font-family: 'Lato', sans-serif;
  color: #333;
}

/* Header */
.navbar-custom {
  background-color: #002147;
  color: white;
  padding: 15px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.navbar-custom a {
  color: #FDB913;
  text-decoration: none;
}
.navbar-custom a:hover {
  color: white;
}

/* Page Title */
.page-title h3 {
  color: #002147;
  font-weight: 700;
  margin-bottom: 30px;
}

/* Panel Styling */
.panel {
  border-radius: 10px;
  border: 1px solid #ddd;
  box-shadow: 0 3px 6px rgba(0,0,0,0.08);
}

.panel-heading {
  background-color: #002147;
  color: #fff;
  padding: 15px 20px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.panel-heading h3 {
  margin: 0;
  font-weight: 600;
}

.panel-body {
  background-color: #fff;
  padding: 25px;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
}

.panel-footer {
  background-color: #f9f9f9;
  border-top: 1px solid #eee;
  padding: 15px 20px;
  border-radius: 0 0 10px 10px;
}

/* Form Controls */
.input-group-addon {
  background-color: #FDB913;
  color: #002147;
  font-weight: 700;
  border: none;
}

.form-control {
  border-radius: 6px;
  border: 1px solid #ccc;
}

.btn-primary {
  background-color: #FDB913;
  border-color: #FDB913;
  color: #002147;
  font-weight: 700;
  text-transform: uppercase;
  border-radius: 6px;
}

.btn-primary:hover {
  background-color: #e0a800;
  border-color: #e0a800;
  color: #fff;
}

.btn-default {
  border-radius: 6px;
}

/* Footer */
.footer-widget {
  background-color: #002147;
  color: #fff;
  padding: 15px 0;
  text-align: center;
  font-size: 13px;
  margin-top: 40px;
}
</style>
</head>

<body>

<!-- Header -->
<div class="navbar-custom">
  <div class="container">
    <a href="dashboard.php">← Back to Dashboard</a>
    <span class="float-right">Welcome, <?= $_SESSION['name']; ?></span>
  </div>
</div>

<div class="container mt-5">
  <div class="page-title text-center">
    <h3><?= $_SESSION['name']; ?>'s Profile</h3>
  </div>

  <?php
  $query = mysqli_query($con, "SELECT * FROM user WHERE email='" . $_SESSION['login'] . "'");
  while ($row = mysqli_fetch_array($query)) {
  ?>

  <form class="form-horizontal" method="post" enctype="multipart/form-data">
    <div class="panel">
      <div class="panel-heading">
        <h3>Your Profile</h3>
        <small>Registration Date: <?= $row['posting_date']; ?></small>
      </div>

      <div class="panel-body">
        <div class="form-group row">
          <label class="col-md-3 control-label">Full Name</label>
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" name="name" value="<?= $row['name']; ?>" class="form-control"/>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 control-label">Primary Email</label>
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
              <input type="email" name="email" value="<?= $row['email']; ?>" class="form-control" disabled/>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 control-label">Alternate Email</label>
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-envelope-open"></i></span>
              <input type="email" name="alt_email" value="<?= $row['alt_email']; ?>" class="form-control"/>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 control-label">Phone Number</label>
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-phone"></i></span>
              <input type="text" name="phone" value="<?= $row['mobile']; ?>" maxlength="10" class="form-control"/>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 control-label">Gender</label>
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-venus-mars"></i></span>
              <select class="form-control" name="gender">
                <option value="male" <?= $row['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?= $row['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 control-label">Address</label>
          <div class="col-md-6">
            <textarea class="form-control" name="address" rows="4"><?= $row['address']; ?></textarea>
          </div>
        </div>
      </div>

      <div class="panel-footer text-right">
        <button class="btn btn-default" type="reset">Clear Form</button>
        <input type="submit" value="Update" name="update" class="btn btn-primary">
      </div>
    </div>
  </form>

  <?php } ?>
</div>

<div class="footer-widget">
  © <?= date("Y"); ?> Office of the Registrar of Political Parties (ORPP) — Customer Service System
</div>

<!-- JS -->
<script src="assets/plugins/jquery-1.8.3.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
