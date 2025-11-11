<?php
session_start();
error_reporting(0);
include("dbconnection.php");

if (isset($_POST['login'])) {
    $ret = mysqli_query($con, "SELECT * FROM user WHERE email='" . $_POST['email'] . "' and password='" . $_POST['password'] . "'");
    $num = mysqli_fetch_array($ret);
    if ($num > 0) {
        $_SESSION['login'] = $_POST['email'];
        $_SESSION['id'] = $num['id'];
        $_SESSION['name'] = $num['name'];

        $val3 = date("Y/m/d");
        date_default_timezone_set("Africa/Nairobi");
        $time = date("h:i:sa");
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $geopluginURL = 'http://www.geoplugin.net/php.gp?ip=' . $ip_address;
        $addrDetailsArr = @unserialize(file_get_contents($geopluginURL));
        $city = $addrDetailsArr['geoplugin_city'] ?? '';
        $country = $addrDetailsArr['geoplugin_countryName'] ?? '';

        mysqli_query($con, "INSERT INTO usercheck(logindate,logintime,user_id,username,email,ip,city,country)
        VALUES('$val3','$time','{$_SESSION['id']}','{$_SESSION['name']}','{$_SESSION['login']}','$ip_address','$city','$country')");

        echo "<script>window.location.href='dashboard.php'</script>";
        exit();
    } else {
        $_SESSION['action1'] = "Invalid username or password";
        echo "<script>window.location.href='login.php'</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ORPP | User Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Catamaran:400,600,700|Lato:400,700&display=swap" rel="stylesheet">

<style>
body {
  font-family: 'Lato', sans-serif;
  background-color: #f4f4f4;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-container {
  background: #fff;
  border-top: 5px solid #003366; /* ORPP navy blue */
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 420px;
  padding: 35px;
}

.login-container h2 {
  color: #003366;
  font-weight: 700;
  text-align: center;
  margin-bottom: 10px;
}

.login-container p {
  text-align: center;
  color: #555;
}

.form-control {
  border-radius: 6px;
  border: 1px solid #ccc;
  box-shadow: none;
}

.btn-primary {
  background-color: #003366;
  border: none;
  font-weight: 600;
  border-radius: 30px;
  transition: all 0.3s;
  color: #fff;
}

.btn-primary:hover {
  background-color: #00224d;
}

.text-link {
  color: #003366;
  font-weight: 600;
}

.text-link:hover {
  color: #FFD700;
  text-decoration: underline;
}

.error-msg {
  color: #d9534f;
  text-align: center;
  margin-bottom: 15px;
}

footer {
  text-align: center;
  color: #555;
  font-size: 14px;
  padding: 15px 0;
  background-color: #fff;
  border-top: 3px solid #003366;
}
</style>
</head>

<body>
  <main>
    <div class="login-container">
      <div class="text-center mb-3">
        <img src="assets/img/orpp-logo.png" alt="ORPP Logo" style="width: 80px; margin-bottom: 10px;">
      </div>

      <h2>Welcome Back</h2>
      <p class="mb-4">Sign in to access your account</p>

      <?php if(!empty($_SESSION['action1'])): ?>
        <p class="error-msg"><?= $_SESSION['action1']; ?></p>
        <?php $_SESSION['action1'] = ""; ?>
      <?php endif; ?>

      <form method="post">
        <div class="form-group mb-3">
          <label>Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group mb-4">
          <label>Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary btn-block py-2" name="login">Login</button>
        </div>
      </form>

      <p class="mt-4 text-center">Don’t have an account? <a href="registration.php" class="text-link">Sign up here</a></p>
    </div>
  </main>

  <footer>
    <p>© <?= date('Y') ?> Office of the Registrar of Political Parties | All Rights Reserved</p>
  </footer>

<script src="assets/plugins/jquery-1.8.3.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
