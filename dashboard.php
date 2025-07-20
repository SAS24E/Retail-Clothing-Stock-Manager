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
    <link rel="stylesheet" href="styles.css">
    <script>
        function showNotReady(feature) {
            alert(feature + " feature not added yet.");
        }
    </script>
</head>

<body>
    <h2>Admin Dashboard</h2>
    <hr class="white-line">
    <h2 style="margin-top: 0;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <div class="dashboard-container">
        <!-- Admin-only features -->
        <h3 style="margin-top: 30px;">Manage Inventory</h3>
        <button onclick="window.location.href='add_item.php'">Add Item</button>
        <button onclick="window.location.href='record_sale.php'">Record Sale</button>
        <button onclick="window.location.href='low_stock_alert.php'">Low Stock Alerts</button>
        <button onclick="window.location.href='view_sales.php'">View Sales</button>
        <button onclick="window.location.href='view_inventory.php'">View Inventory</button>


        <!-- Analytics Section -->
        <h3 style="margin-top: 30px;">Analytics</h3>
        <button onclick="window.location.href='analytics/user_stats.php'">User Statistics</button>
        <button onclick="window.location.href='analytics/product_stats.php'">Product Statistics</button>
        <button onclick="window.location.href='analytics/category_analysis.php'">Category Analysis</button>
        <button onclick="window.location.href='analytics/activity_reports.php'">Activity Reports</button>

        <!-- User Management Section -->
        <h3 style="margin-top: 30px;">User Management</h3>
        <button onclick="window.location.href='admin/manage_users.php'">Manage Users</button>
        <button onclick="window.location.href='admin/moderate_items.php'">Moderate Items</button>

        <!-- Support Section -->
        <h3 style="margin-top: 30px;">Support</h3>
        <button onclick="window.location.href='support_pages/view_tickets.php'">View Tickets</button>
        <button onclick="window.location.href='support_pages/support.php'">Submit a Ticket</button>



        <br><br>
        <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>
</body>

</html>