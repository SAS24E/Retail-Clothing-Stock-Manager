<?php
session_start();
require_once '../db_connect.php';

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid or missing user ID.";
    exit;
}

$id = intval($_GET['id']);

// Get current status
$stmt = $conn->prepare("SELECT status FROM user WHERE iduser = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit;
}

$row = $result->fetch_assoc();
$current = $row['status'] ?? 'active';
$newStatus = ($current === 'active') ? 'inactive' : 'active';

// Update
$update = $conn->prepare("UPDATE user SET status = ? WHERE iduser = ?");
$update->bind_param("si", $newStatus, $id);
$update->execute();

// Redirect back
header("Location: manage_users.php");
exit;
?>
