<?php
$host = "localhost";
$dbuser = "your_username";
$dbpass = "your_password";
$dbname = "your_database";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

