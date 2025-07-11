<?php
session_start();
require_once '../db_connect.php';

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

// Get product statistics
$total_products = $conn->query("SELECT COUNT(*) as count FROM product")->fetch_assoc()['count'];
$approved_products = $conn->query("SELECT COUNT(*) as count FROM product WHERE status = 'approved'")->fetch_assoc()['count'];
$pending_products = $conn->query("SELECT COUNT(*) as count FROM product WHERE status = 'pending'")->fetch_assoc()['count'];
$rejected_products = $conn->query("SELECT COUNT(*) as count FROM product WHERE status = 'rejected'")->fetch_assoc()['count'];

// Get low stock items
$low_stock_items = $conn->query("
    SELECT name, quantity, low_stock_threshold 
    FROM product 
    WHERE quantity <= low_stock_threshold AND status = 'approved'
    ORDER BY quantity ASC
    LIMIT 10
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Statistics</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <link rel="stylesheet" href="../styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Product Statistics</h2>
    <div class="analytics-container">
        <!-- Product Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Products</h3>
                <div class="value"><?= $total_products ?></div>
            </div>
            <div class="stat-card">
                <h3>Approved</h3>
                <div class="value"><?= $approved_products ?></div>
            </div>
            <div class="stat-card">
                <h3>Pending</h3>
                <div class="value"><?= $pending_products ?></div>
            </div>
            <div class="stat-card">
                <h3>Rejected</h3>
                <div class="value"><?= $rejected_products ?></div>
            </div>
        </div>

        <!-- Product Status Chart -->
        <div class="chart-container">
            <h3>Product Status Breakdown</h3>
            <canvas id="productStatusChart" height="300"></canvas>
        </div>

        <!-- Low Stock Items -->
        <h3>Low Stock Items (Top 10)</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Current Quantity</th>
                    <th>Threshold</th>
                    <th>Difference</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $low_stock_items->fetch_assoc()): ?>
                <tr class="low-stock">
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['low_stock_threshold'] ?></td>
                    <td><?= $row['quantity'] - $row['low_stock_threshold'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <button onclick="window.location.href='../dashboard.php'" class="logout-button">Back to Dashboard</button>
        </div>
    </div>

    <script>
        // Product Status Chart
        const productStatusCtx = document.getElementById('productStatusChart').getContext('2d');
        const productStatusChart = new Chart(productStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected'],
                datasets: [{
                    data: [<?= $approved_products ?>, <?= $pending_products ?>, <?= $rejected_products ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>
</body>
</html>
