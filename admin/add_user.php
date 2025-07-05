<?php
session_start();
require_once '../db_connect.php';

// Only allow access if logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Basic validation
    if (empty($username) || empty($password) || empty($role)) {
        echo "All fields are required.";
        exit;
    }

    // Only allow specific roles
    if (!in_array($role, ['admin', 'employee'])) {
        echo "Invalid role selected.";
        exit;
    }

    // Insert new user with default 'active' status and current join date
    $stmt = $conn->prepare("INSERT INTO user (username, password, role, status, join_date) VALUES (?, ?, ?, 'active', NOW())");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        header("Location: manage_users.php");
        exit;
    } else {
        echo "Failed to add user.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
    <style>
        form {
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        button {
            padding: 10px 15px;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Add New User</h2>

<form method="post">
    <label>Username:</label>
    <input type="text" name="username" required>

    <label>Password:</label>
    <input type="text" name="password" required>

    <label>Role:</label>
    <select name="role" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="employee">Employee</option>
    </select>

    <button type="submit">Add User</button>
</form>

<div style="text-align:center;">
    <a href="manage_users.php">⬅ Back to User Management</a>
</div>

</body>
</html>
