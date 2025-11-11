<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("dbconnection.php"); // PostgreSQL connection

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // PostgreSQL table "user" is case-sensitive and reserved, so quotes needed
    $ret = pg_query_params($con, 'SELECT * FROM "user" WHERE email=$1 AND password=$2', array($email, $password));
    if (!$ret) {
        die("Query failed: " . pg_last_error($con));
    }

    $num = pg_fetch_assoc($ret);

    if ($num) {
        $_SESSION['login'] = $email;
        $_SESSION['id'] = $num['id'];
        $_SESSION['name'] = $num['name'];

        date_default_timezone_set("Africa/Nairobi");
        $val3 = date("Y/m/d");
        $time = date("h:i:sa");
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $geopluginURL = 'http://www.geoplugin.net/php.gp?ip=' . $ip_address;
        $addrDetailsArr = @unserialize(file_get_contents($geopluginURL));
        $city = $addrDetailsArr['geoplugin_city'] ?? '';
        $country = $addrDetailsArr['geoplugin_countryName'] ?? '';

        // Insert login info
        $insert = pg_query_params($con, 
            'INSERT INTO usercheck(logindate, logintime, user_id, username, email, ip, city, country)
             VALUES($1,$2,$3,$4,$5,$6,$7,$8)', 
            array($val3, $time, $_SESSION['id'], $_SESSION['name'], $_SESSION['login'], $ip_address, $city, $country)
        );

        if (!$insert) {
            die("Insert failed: " . pg_last_error($con));
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['action1'] = "Invalid username or password";
        header("Location: login.php");
        exit();
    }
}
?>
