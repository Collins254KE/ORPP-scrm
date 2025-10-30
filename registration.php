<?php
session_start();
error_reporting(0);
include("dbconnection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['phone'];
    $gender = $_POST['gender'];

    $query = mysqli_query($con, "SELECT email FROM user WHERE email='$email'");
    $num = mysqli_fetch_array($query);

    if ($num > 1) {
        echo "<script>alert('Email already registered. Please use a different email.');</script>";
        echo "<script>window.location.href='registration.php'</script>";
    } else {
        mysqli_query($con, "INSERT INTO user(name, email, password, mobile, gender) VALUES('$name', '$email', '$password', '$mobile', '$gender')");
        echo "<script>alert('Your account has been created successfully.');</script>";
        echo "<script>window.location.href='login.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ORPP | User Registration</title>
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

.register-container {
  background: #fff;
  border-top: 5px solid #003366; /* ORPP navy blue */
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 460px;
  padding: 35px;
}

.register-container h2 {
  color: #003366;
  font-weight: 700;
  text-align: center;
  margin-bottom: 15px;
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

label {
  font-weight: 600;
  color: #333;
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

<script>
function checkpass() {
  if (document.signup.password.value != document.signup.cpassword.value) {
    alert('Passwords do not match!');
    document.signup.cpassword.focus();
    return false;
  }
  return true;
}
</script>
</head>

<body>
  <main>
    <div class="register-container">
      <div class="text-center mb-3">
        <img src="assets/img/orpp-logo.png" alt="ORPP Logo" style="width: 80px; margin-bottom: 10px;">
      </div>

      <h2>Create Your Account</h2>
      <p class="text-center mb-4">Already registered? <a href="login.php" class="text-link">Login here</a></p>

      <form name="signup" method="post" onsubmit="return checkpass();">
        <div class="form-group mb-3">
          <label>Name</label>
          <input type="text" class="form-control" name="name" required>
        </div>

        <div class="form-group mb-3">
          <label>Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group mb-3">
          <label>Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group mb-3">
          <label>Confirm Password</label>
          <input type="password" class="form-control" name="cpassword" required>
        </div>

        <div class="form-group mb-3">
          <label>Phone Number</label>
          <input type="text" pattern="[0-9]{10}" maxlength="10" class="form-control" name="phone" required placeholder="e.g. 0712345678">
          <small class="text-muted">Must be 10 digits (e.g., 07XXXXXXXX)</small>
        </div>

        <div class="form-group mb-4">
          <label>Gender</label>
          <select class="form-control" name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary btn-block py-2">Create Account</button>
        </div>
      </form>
    </div>
  </main>

  <footer>
    <p>© <?= date('Y') ?> Office of the Registrar of Political Parties | All Rights Reserved</p>
  </footer>
</body>
</html>
