<?php
session_start();
include 'db_connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Simple comparison for plain text (not secure)
if ($user && $password === $user['password']) {
    $_SESSION['user_id'] = $user['iduser'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    header("Location: dashboard.html");
    exit;
} else {
    echo "<h3>Invalid login. <a href='index.html'>Try again</a></h3>";
}
?>
