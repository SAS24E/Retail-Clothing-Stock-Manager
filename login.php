<?php

// start session to store user login state
session_start();

include 'db_connect.php';

// retrieve the username and password submitted
$username = $_POST['username'];
$password = $_POST['password'];

// prepare a SQL statement to prevent SQL injection
$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

// get the result and fetch the data as an associative array
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// comparison with the login information matching the users stored in the database
if ($user && $password === $user['password']) {
    $_SESSION['user_id'] = $user['iduser'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    header("Location: dashboard.php");
    exit;
} else {
    echo "<h3>Invalid login. <a href='index.html'>Try again</a></h3>";
}
?>
