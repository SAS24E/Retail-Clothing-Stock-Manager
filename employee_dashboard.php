<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
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
    <link rel="stylesheet" href="styles.css"> 
    <script>
        function showNotReady(feature) {
            alert(feature + " feature not added yet.");
        }
    </script>
</head>
<body>
    <h2>Employee Dashboard</h2>
    <div class="dashboard-container">
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

        <!-- Employee features -->
        <button onclick="window.location.href='view_inventory.php'">View Inventory</button>
        <button onclick="window.location.href='add_item.php'">Add Item</button>
        <button onclick="window.location.href='record_sale.php'">Record Sale</button>
        <button onclick="window.location.href='support_pages/support.php'">Support</button>
        <br><br>
        <!-- support section -->
        <h3 style="margin-top: 30px;">Support</h3>
        <button onclick="window.location.href='support_pages/view_tickets.php'">View My Tickets</button>
        <button onclick="window.location.href='support_pages/support.php'">Submit a Ticket</button>
        <br><br>
        <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
