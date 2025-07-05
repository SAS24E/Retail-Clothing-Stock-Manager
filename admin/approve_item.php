<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE product SET status = 'approved' WHERE idproduct = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: moderate_items.php");
exit;
?>
