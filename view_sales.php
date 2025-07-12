<?php
session_start();
require_once 'db_connect.php';

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit;
}

// Get all sales with product and user info
$sales = $conn->query("
    SELECT 
        s.quantity,
        s.sold_at,
        u.username,
        p.name AS product_name
    FROM sales_log s
    JOIN user u ON s.user_iduser = u.iduser
    JOIN product p ON s.product_idproduct = p.idproduct
    ORDER BY s.sold_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Sales</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .analytics-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .sales-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .sales-table th, .sales-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .sales-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Sales Log</h2>
    <div class="analytics-container">
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                    <th>Sold At</th>
                    <th>Sold By</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($sales->num_rows > 0): ?>
                    <?php while ($row = $sales->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($row['sold_at'])) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No sales recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <button onclick="window.location.href='dashboard.php'" class="logout-button">Back to Dashboard</button>
        </div>
    </div>
</body>
</html>
