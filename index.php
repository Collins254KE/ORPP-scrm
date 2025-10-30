<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ORPP Customer Service System</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Catamaran:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Lato', sans-serif;
            background-color: #f5f7fa;
            display: flex;
            flex-direction: column;
        }

        /* ORPP Logo Section */
        .orpp-logo-section {
            background: #fff;
            border-bottom: 3px solid #FDB913;
            padding: 20px 0;
            text-align: center;
        }

        .orpp-logo-section .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .orpp-logo-section .container {
                flex-direction: row;
            }
        }

        .orpp-logo {
            height: 120px;
            width: auto;
            margin-right: 20px;
        }

        .orpp-logo-section h2 {
            font-family: 'Catamaran', sans-serif;
            font-weight: 800;
            color: #003366;
            margin-bottom: 0;
        }

        .orpp-logo-section p {
            font-size: 1.1rem;
            color: #007aff;
            font-weight: 600;
            margin-bottom: 0;
        }

        /* Navbar */
        .navbar-custom {
            background-color: #003366;
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
            background: linear-gradient(to bottom right, #ffffff, #f3f6fa);
            color: #003366;
            padding: 120px 0;
            position: relative;
            flex: 1 0 auto;
        }

        header .masthead-content {
            position: relative;
            z-index: 2;
        }

        header::before {
            content: "";
            background: url('{{ asset("assets/images/orpp-logo.png") }}') no-repeat center;
            background-size: 120px;
            opacity: 0.05;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
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

        /* Info Box */
        .info-box {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: left;
        }

        .info-box h4 {
            color: #003366;
            font-weight: 700;
            margin-top: 1.5rem;
        }

        /* Footer */
        footer {
            background-color: #003366;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            flex-shrink: 0;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- ORPP Logo Section -->
    <section class="orpp-logo-section">
        <div class="container">
            <img src="{{ asset('assets/img/orpp-logo.png') }}" alt="ORPP Logo" class="orpp-logo">
            <div>
                <h2>Office of the Registrar of Political Parties (ORPP)</h2>
                <p>Customer Service Desk</p>
            </div>
        </div>
    </section>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">ORPP</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ asset('registration.php') }}">Signup</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ asset('login.php') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ asset('admin/') }}">Admin Panel</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="masthead text-center">
        <div class="masthead-content">
            <div class="container">
                <marquee behavior="alternate" direction="left" scrollamount="6" style="font-size:1.8rem;font-weight:700;color:#003366;">
                    Welcome to the ORPP Customer Service System
                </marquee>
                <p class="lead mb-5">Enhancing citizen engagement through efficient service and accountability.</p>

                <!-- Vision, Mission, Core Values -->
                <div class="mt-5 info-box">
                    <h4>Vision</h4>
                    <p>A model regulator of political parties for a credible democratic multiparty system.</p>

                    <h4>Mission</h4>
                    <p>To promote the realization of political rights through registration and regulation of political parties in Kenya.</p>

                    <h4>Mandate</h4>
                    <p>To register and regulate political parties and administer the Political Parties Fund.</p>

                    <h4>Core Values</h4>
                    <ul class="list-unstyled mb-0">
                        <li>✔️ Integrity</li>
                        <li>✔️ Professionalism</li>
                        <li>✔️ Accountability</li>
                        <li>✔️ Teamwork</li>
                        <li>✔️ Fairness</li>
                        <li>✔️ Transparency</li>
                        <li>✔️ Respect for Diversity</li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© 2025 Office of the Registrar of Political Parties (ORPP) — Customer Service Desk | {{ date('Y-m-d') }}</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
