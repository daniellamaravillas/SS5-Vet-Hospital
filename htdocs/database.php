<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "127.0.0.1";
$username = "mariadb";
$password = "mariadb";
$dbname = "mariadb";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";


?>