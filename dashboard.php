<?php
session_start();
include("checklogin.php");
check_login();
include("dbconnection.php"); // use PostgreSQL connection

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>ORPP | Customer Service Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" />
<link href="assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" />
<link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" />
<link href="assets/css/animate.min.css" rel="stylesheet" />
<link href="assets/css/style.css" rel="stylesheet" />
<link href="assets/css/responsive.css" rel="stylesheet" />
<link href="assets/css/custom-icon-set.css" rel="stylesheet" />

<style>
/* ===== ORPP COLOR THEME ===== */
body {
  background-color: #F5F7FA;
  font-family: 'Segoe UI', sans-serif;
  color: #003366;
}
.navbar, .header { background-color: #003366 !important; border-bottom: 4px solid #FDB913; }
.navbar-brand, .navbar-nav > li > a { color: #ffffff !important; font-weight: 600; }
.page-title h3 { color: #003366; font-weight: 700; border-left: 5px solid #FDB913; padding-left: 10px; }
.tiles.blue { background: #003366 !important; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.tiles .heading a { color: #FDB913 !important; font-size: 18px; font-weight: 600; text-decoration: none; }
.tiles-body h3 { color: #ffffff; font-size: 36px; font-weight: 700; }
.footer { background-color: #003366; color: #ffffff; padding: 15px 0; text-align: center; border-top: 3px solid #FDB913; margin-top: 30px; }
.animate-number { color: #FDB913; }
a:hover { color: #FDB913; }
</style>
</head>

<body>
<?php include("header.php"); ?>

<div class="page-container row-fluid">  
  <?php include("leftbar.php"); ?>
  
  <div class="page-content"> 
    <div class="content">  
      <div class="page-title">  
        <h3>Dashboard</h3>  
      </div>
      
      <div class="row">
        <div class="col-md-6 col-sm-6">
          <div class="tiles blue added-margin">
            <div class="tiles-body">
              <?php 
              // PostgreSQL query
              $ret = pg_query($con, "SELECT * FROM ticket WHERE email_id='" . $_SESSION['login'] . "'");
              $num = pg_num_rows($ret);
              ?>
              <div class="heading">
                <a href="view-tickets.php">Total Tickets</a>
              </div>
              <h3 class="text-right">
                <span class="animate-number" data-value="<?php echo $num;?>" data-animation-duration="1200"><?= $num ?></span>
              </h3>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="footer">
      &copy; <?= date('Y'); ?> Office of the Registrar of Political Parties (ORPP). All Rights Reserved.
    </footer>
  </div>
</div>

<script src="assets/plugins/jquery-1.8.3.min.js"></script> 
<script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script> 
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script> 
<script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/plugins/pace/pace.min.js"></script>  
<script src="assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js"></script>
<script src="assets/js/core.js"></script> 
<script src="assets/js/chat.js"></script> 
<script src="assets/js/demo.js"></script> 
</body>
</html>
