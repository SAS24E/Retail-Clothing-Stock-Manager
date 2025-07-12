<?php
session_start();
require_once '../db_connect.php';

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

// Handle category filter (can be multiple)
$selected_categories = isset($_GET['categories']) ? $_GET['categories'] : [];

// Get list of all categories
$all_categories = [];
$result = $conn->query("SELECT DISTINCT category FROM product WHERE status = 'approved'");
while ($row = $result->fetch_assoc()) {
    $all_categories[] = $row['category'];
}

// Build SQL
$where_clause = "status = 'approved'";
if (!empty($selected_categories)) {
    $escaped = array_map(function($cat) use ($conn) {
        return "'" . $conn->real_escape_string($cat) . "'";
    }, $selected_categories);
    $where_clause .= " AND category IN (" . implode(",", $escaped) . ")";
}

$category_breakdown = $conn->query("
    SELECT category, COUNT(*) as count, SUM(quantity) as total_quantity
    FROM product
    WHERE $where_clause
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
</head>
<body>
    <h2>Category Analysis</h2>
    <div class="analytics-container">
        <!-- Multi-Category Checkbox Filter -->
        <div class="filter-form">
            <form method="GET">
                <h3>Filter by Category:</h3>
                <?php foreach ($all_categories as $cat): ?>
                    <label style="margin-right: 15px;">
                        <input type="checkbox" name="categories[]" value="<?= htmlspecialchars($cat) ?>"
                            <?= in_array($cat, $selected_categories) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </label>
                <?php endforeach; ?>
                <br><br>
                <button type="submit" style="padding: 8px 15px;">Apply Filter</button>
                <a href="category_analysis.php" style="padding: 8px 15px; background: #e74c3c; color: white; border-radius: 4px; text-decoration: none;">Reset</a>
            </form>
        </div>

        <!-- Chart -->
        <div class="chart-container">
            <h3>Product Categories</h3>
            <canvas id="categoryChart" height="300"></canvas>
        </div>

        <!-- Table -->
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
                <?php 
                $category_data = [];
                while ($row = $category_breakdown->fetch_assoc()):
                    $category_data[] = $row;
                ?>
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
        // Build chart data from PHP
        const categories = <?= json_encode(array_column($category_data, 'category')) ?>;
        const productCounts = <?= json_encode(array_column($category_data, 'count')) ?>;

        const ctx = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Number of Products',
                    data: productCounts,
                    backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, precision: 0 }
                }
            }
        });
    </script>
</body>
</html>
