<?php
// edit_users.php
session_start();
require_once '../db_connect.php';

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

$id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($password)) {
        // Update username, password, and role
        $stmt = $conn->prepare("UPDATE user SET username=?, password=?, role=? WHERE iduser=?");
        $stmt->bind_param("sssi", $username, $password, $role, $id);
    } else {
        // Update username and role only
        $stmt = $conn->prepare("UPDATE user SET username=?, role=? WHERE iduser=?");
        $stmt->bind_param("ssi", $username, $role, $id);
    }

    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}

// Fetch user
$user = $conn->query("SELECT * FROM user WHERE iduser=$id")->fetch_assoc();
?>

<h2>Edit User</h2>
<form method="post">
    Username: <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

    Password: <input type="text" name="password" placeholder="Leave blank to keep current"><br><br>

    Role:
    <select name="role" required>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
        <option value="employee" <?= $user['role'] === 'employee' ? 'selected' : '' ?>>Employee</option>
    </select><br><br>

    <button type="submit">Update</button>
</form>
