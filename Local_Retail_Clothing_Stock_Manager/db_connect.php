<?php

// information for connecting database
$host = "localhost";
$dbuser = "root";
$dbpass = ""; 
$dbname = "clothestock";

// create new connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
