<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
</head>
<body>
    <h2>Employee Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <!-- Only show inventory access -->
    <button onclick="window.location.href='view_inventory.php'">View Inventory</button>
    <button onclick="window.location.href='add_item.php'">Add Item</button>
    <br><br>
    <button onclick="window.location.href='logout.php'">Logout</button>
</body>
</html>
