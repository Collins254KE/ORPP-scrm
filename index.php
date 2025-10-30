<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ORPP Customer Service System</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Catamaran:wght@400;700;900&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Lato', sans-serif;
      background-color: #f4f6f9;
    }

    /* Navbar */
    .navbar-custom {
      background-color: #002147; /* ORPP navy blue */
    }
    .navbar-custom .navbar-brand {
      color: #FDB913;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .navbar-custom .nav-link {
      color: #fff !important;
      font-weight: 600;
    }
    .navbar-custom .nav-link:hover {
      color: #FDB913 !important;
    }

    /* Header Section */
    header.masthead {
      background: linear-gradient(to bottom right, #002147, #004080);
      color: white;
      padding: 120px 0;
      position: relative;
    }

    header .masthead-content {
      position: relative;
      z-index: 2;
    }

    header::before {
      content: "";
      background: url('assets/images/orpp-logo.png') no-repeat center;
      background-size: 120px;
      opacity: 0.05;
      position: absolute;
      top: 0; left: 0;
      right: 0; bottom: 0;
      z-index: 1;
    }

    header h1 {
      font-family: 'Catamaran', sans-serif;
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 20px;
    }

    .btn-primary {
      background-color: #FDB913;
      border-color: #FDB913;
      color: #002147;
      font-weight: 700;
      padding: 12px 30px;
      border-radius: 50px;
      text-transform: uppercase;
    }

    .btn-primary:hover {
      background-color: #e0a800;
      border-color: #e0a800;
      color: #fff;
    }

    /* Footer */
    footer {
      background-color: #002147;
      color: #fff;
      padding: 20px 0;
    }

    footer p {
      margin: 0;
      font-size: 14px;
    }
  </style>
</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">ORPP - Customer Service Desk</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
              aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="registration.php">Signup</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="admin/">Admin Panel</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <header class="masthead text-center text-white">
    <div class="masthead-content">
      <div class="container">
        <h1>Welcome to the ORPP Customer Service System</h1>
        <p class="lead mb-5">Enhancing citizen engagement through efficient service and accountability.</p>
        <a href="registration.php" class="btn btn-primary btn-xl rounded-pill mt-3">User Signup</a>
      </div>
    </div>
  </header>

  <!-- Footer -->
  <footer class="text-center">
    <div class="container">
      <p>© 2025 Office of the Registrar of Political Parties (ORPP) — Customer Service Desk | <?= date("Y-m-d") ?></p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
