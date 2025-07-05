<?php
session_start();
require_once '../db_connect.php';

// Only allow access if logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

$result = $conn->query("SELECT * FROM user");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
        .center {
            text-align: center;
            margin-top: 20px;
        }
        .add-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .add-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">User Management</h2>

<div class="center">
    <a href="add_user.php" class="add-button">➕ Add New User</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Status</th>
        <th>Join Date</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['iduser'] ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= $row['role'] ?></td>
        <td><?= $row['status'] ?? 'active' ?></td>
        <td><?= isset($row['join_date']) ? date("Y-m-d", strtotime($row['join_date'])) : 'N/A' ?></td>
        <td>
            <a href="edit_users.php?id=<?= $row['iduser'] ?>">Edit</a> |
            <a href="delete_users.php?id=<?= $row['iduser'] ?>" onclick="return confirm('Are you sure?')">Delete</a> |
            <a href="toggle_users.php?id=<?= $row['iduser'] ?>">Toggle Status</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div class="center">
    <a href="../dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
