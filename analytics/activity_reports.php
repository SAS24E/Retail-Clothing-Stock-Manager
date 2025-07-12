<?php
session_start();
require_once '../db_connect.php';

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

// Date range filter (default: last 30 days)
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// User role filter
$role_filter = isset($_GET['role']) && in_array($_GET['role'], ['admin', 'employee']) ? $_GET['role'] : null;

// Get recent activity
$recent_activity = $conn->query("
    SELECT 
        u.username,
        u.role,
        COUNT(p.idproduct) as products_added
    FROM user u
    LEFT JOIN product p ON u.iduser = p.added_by
    WHERE p.created_at BETWEEN '$start_date' AND '$end_date 23:59:59'
    " . ($role_filter ? " AND u.role = '$role_filter'" : "") . "
    GROUP BY u.iduser
    ORDER BY products_added DESC
    LIMIT 10
");

// Get activity trends
$activity_trends = $conn->query("
    SELECT 
        DATE(p.created_at) as date,
        COUNT(p.idproduct) as products_added
    FROM product p
    WHERE p.created_at BETWEEN '$start_date' AND '$end_date 23:59:59'
    GROUP BY DATE(p.created_at)
    ORDER BY date
");

// Get sales trends
$sales_trends = $conn->query("
    SELECT 
        DATE(sold_at) as date,
        SUM(quantity) as items_sold
    FROM sales_log
    WHERE sold_at BETWEEN '$start_date' AND '$end_date 23:59:59'
    GROUP BY DATE(sold_at)
    ORDER BY date
");

// Build base array with products_added
$activity_data = [];
$activity_trends->data_seek(0);
while ($row = $activity_trends->fetch_assoc()) {
    $activity_data[$row['date']] = ['products_added' => (int)$row['products_added'], 'items_sold' => 0];
}

// Overlay items_sold
$sales_trends->data_seek(0);
while ($row = $sales_trends->fetch_assoc()) {
    $date = $row['date'];
    $items_sold = (int)$row['items_sold'];

    if (!isset($activity_data[$date])) {
        $activity_data[$date] = ['products_added' => 0, 'items_sold' => $items_sold];
    } else {
        $activity_data[$date]['items_sold'] = $items_sold;
    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>Activity Reports</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <h2>Activity Reports</h2>
    <div class="analytics-container">
        <!-- Filter Form -->
        <div class="filter-form">
            <h3>Filter Data</h3>
            <form method="GET">
                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="<?= $start_date ?>" style="width: 100%; padding: 8px;">
                    </div>
                    <div style="flex: 1;">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="<?= $end_date ?>" style="width: 100%; padding: 8px;">
                    </div>
                    <div style="flex: 1;">
                        <label for="role">User Role</label>
                        <select id="role" name="role" style="width: 100%; padding: 8px;">
                            <option value="">All Roles</option>
                            <option value="admin" <?= $role_filter === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="employee" <?= $role_filter === 'employee' ? 'selected' : '' ?>>Employee</option>
                        </select>
                    </div>
                </div>
                <button type="submit" style="padding: 10px 15px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">Apply Filters</button>
                <button type="button" style="padding: 10px 15px; background-color: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;" onclick="window.location.href='activity_reports.php'">Reset</button>
            </form>
        </div>

        <!-- Activity Trends Chart -->
        <div class="chart-container">
            <h3>Activity Over Time</h3>
            <canvas id="activityChart" height="300"></canvas>
        </div>

        <!-- Top Contributors -->
        <h3>Top Contributors</h3>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Products Added</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $recent_activity->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= ucfirst($row['role']) ?></td>
                        <td><?= $row['products_added'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <button onclick="window.location.href='../dashboard.php'" class="logout-button">Back to Dashboard</button>
        </div>
    </div>

    <script>
        // Activity Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: [
                    <?php
                    foreach (array_keys($activity_data) as $date): echo "'$date',";
                    endforeach;
                    ?>
                ],
                datasets: [{
                        label: 'Products Added',
                        data: [
                            <?php foreach ($activity_data as $day): echo $day['products_added'] . ",";
                            endforeach; ?>
                        ],
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    },
                    {
                        label: 'Items Sold',
                        data: [
                            <?php foreach ($activity_data as $day): echo $day['items_sold'] . ",";
                            endforeach; ?>
                        ],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    }
                ]

            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
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