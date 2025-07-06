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

if ($user) {
    if ($user['status'] === 'inactive') {
        echo "<h3>Your account is deactivated. <a href='index.html'>Return</a></h3>";
    } elseif ($password === $user['password']) {
        $_SESSION['user_id'] = $user['iduser'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'employee') {
            header("Location: employee_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        echo "<h3>Invalid password. <a href='index.html'>Try again</a></h3>";
    }
} else {
    echo "<h3>User not found. <a href='index.html'>Try again</a></h3>";
}
?>
