<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

$id = intval($_GET['id']);
$conn->query("DELETE FROM user WHERE iduser = $id");

header("Location: manage_users.php");
exit;
?>
