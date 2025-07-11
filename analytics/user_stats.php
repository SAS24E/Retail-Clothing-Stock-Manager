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

// Get user statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM user")->fetch_assoc()['count'];
$active_users = $conn->query("SELECT COUNT(*) as count FROM user WHERE status = 'active'")->fetch_assoc()['count'];
$inactive_users = $total_users - $active_users;

// Get user registration trends
$registration_trends = $conn->query("
    SELECT 
        DATE(join_date) as date, 
        COUNT(*) as count 
    FROM user 
    WHERE join_date BETWEEN '$start_date' AND '$end_date 23:59:59'
    GROUP BY DATE(join_date)
    ORDER BY date
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Statistics</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .analytics-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .stat-card .value {
            font-size: 2.5em;
            font-weight: bold;
            margin: 10px 0;
        }
        .chart-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .filter-form {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <h2>User Statistics</h2>
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
                </div>
                <button type="submit" style="padding: 10px 15px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">Apply Filters</button>
                <button type="button" style="padding: 10px 15px; background-color: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;" onclick="window.location.href='user_stats.php'">Reset</button>
            </form>
        </div>

        <!-- User Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="value"><?= $total_users ?></div>
            </div>
            <div class="stat-card">
                <h3>Active Users</h3>
                <div class="value"><?= $active_users ?></div>
            </div>
            <div class="stat-card">
                <h3>Inactive Users</h3>
                <div class="value"><?= $inactive_users ?></div>
            </div>
        </div>

        <!-- User Registration Chart -->
        <div class="chart-container">
            <h3>User Registrations Over Time</h3>
            <canvas id="registrationChart" height="300"></canvas>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <button onclick="window.location.href='../dashboard.php'" class="logout-button">Back to Dashboard</button>
        </div>
    </div>

    <script>
        // User Registration Chart
        const registrationCtx = document.getElementById('registrationChart').getContext('2d');
        const registrationChart = new Chart(registrationCtx, {
            type: 'line',
            data: {
                labels: [
                    <?php 
                    $registration_trends->data_seek(0);
                    while ($row = $registration_trends->fetch_assoc()): 
                        echo "'" . $row['date'] . "',";
                    endwhile; 
                    ?>
                ],
                datasets: [{
                    label: 'New Registrations',
                    data: [
                        <?php 
                        $registration_trends->data_seek(0);
                        while ($row = $registration_trends->fetch_assoc()): 
                            echo $row['count'] . ",";
                        endwhile; 
                        ?>
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
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

