<?php

// information for connecting database
$host = "sql210.infinityfree.com";
$dbuser = "if0_39284743";
$dbpass = "ZYNKXjRUXaoQd"; 
$dbname = "if0_39284743_Retail_db";

// create new connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

