<?php
// PostgreSQL connection using environment variables
$db_host = getenv('DB_HOST') ?: 'dpg-d49erks9c44c73bkparg-a.oregon-postgres.render.com';
$db_user = getenv('DB_USER') ?: 'postgresql_csm_user';
$db_pass = getenv('DB_PASS') ?: '9kOOhzIbGxIhrSkcvZ4ZKDgl9KxoCs6S';
$db_name = getenv('DB_NAME') ?: 'postgresql_csm';
$db_port = getenv('DB_PORT') ?: '5432';

// Build connection string
$conn_string = "host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass";

// Connect to PostgreSQL
$con = pg_connect($conn_string);

// Check connection
if (!$con) {
    die("Connection failed: " . pg_last_error());
}
?>
