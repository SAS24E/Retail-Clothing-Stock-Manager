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
    <title>Admin Dashboard</title>
    <script>
        function showNotReady(feature) {
            alert(feature + " feature not added yet.");
        }
    </script>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <!-- Admin-only features -->
    <button onclick="window.location.href='add_item.php'">Add Item</button>
    <button onclick="showNotReady('Record Sale')">Record Sale</button>
    <button onclick="showNotReady('View Stock Alerts')">View Stock Alerts</button>
    <button onclick="showNotReady('View Sales')">View Sales</button>
    <button onclick="window.location.href='view_inventory.php'">View Inventory</button>
    <br><br>
    <button onclick="window.location.href='logout.php'">Logout</button>
</body>
</html>
