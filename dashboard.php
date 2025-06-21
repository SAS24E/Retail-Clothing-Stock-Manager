<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script>
        function showNotReady(feature) {
            alert(feature + " feature not added yet.");
        }
    </script>
</head>
<body>
    <h2>Retail Stock Manager Dashboard</h2>

    <button onclick="showNotReady('Add Item')">Add Item</button>
    <button onclick="showNotReady('Record Sale')">Record Sale</button>
    <button onclick="showNotReady('View Stock Alerts')">View Stock Alerts</button>
    <button onclick="showNotReady('View Sales')">View Sales</button>
    <button onclick="window.location.href='view_inventory.php'">View Inventory</button>
    <br><br>
    <button onclick="window.location.href='index.html'">Logout</button>
</body>
</html>