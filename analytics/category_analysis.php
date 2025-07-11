<?php
session_start();
require_once '../db_connect.php';

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

// Get product category breakdown
$category_breakdown = $conn->query("
    SELECT 
        category, 
        COUNT(*) as count,
        SUM(quantity) as total_quantity
    FROM product
    WHERE status = 'approved'
    GROUP BY category
    ORDER BY count DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category Analysis</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Category Analysis</h2>
    <div class="analytics-container">
        <!-- Category Chart -->
        <div class="chart-container">
            <h3>Product Categories</h3>
            <canvas id="categoryChart" height="300"></canvas>
        </div>

        <!-- Category Table -->
        <h3>Category Breakdown</h3>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Number of Products</th>
                    <th>Total Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $category_breakdown->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= $row['count'] ?></td>
                    <td><?= $row['total_quantity'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <button onclick="window.location.href='../dashboard.php'" class="logout-button">Back to Dashboard</button>
        </div>
    </div>

    <script>
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: [
                    <?php 
                    $category_breakdown->data_seek(0);
                    while ($row = $category_breakdown->fetch_assoc()): 
                        echo "'" . htmlspecialchars($row['category']) . "',";
                    endwhile; 
                    ?>
                ],
                datasets: [{
                    label: 'Number of Products',
                    data: [
                        <?php 
                        $category_breakdown->data_seek(0);
                        while ($row = $category_breakdown->fetch_assoc()): 
                            echo $row['count'] . ",";
                        endwhile; 
                        ?>
                    ],
                    backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
</body>
</html>

