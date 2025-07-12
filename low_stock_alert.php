<?php
session_start();
require_once 'db_connect.php';

// Allow only admin users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch low stock products
$low_stock_query = $conn->query("
    SELECT 
        idproduct, name, brand, category, size, color, quantity, low_stock_threshold
    FROM product
    WHERE quantity <= low_stock_threshold
    AND status = 'approved'
    ORDER BY quantity ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alerts</title>
    <link rel="stylesheet" href = styles.css">
    <style>
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        .alert {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>⚠️ Low Stock Alerts</h2>

        <?php if ($low_stock_query->num_rows === 0): ?>
            <p style="color: green;">All stock levels are sufficient ✅</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Threshold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $low_stock_query->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['brand']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['size']) ?></td>
                        <td><?= htmlspecialchars($row['color']) ?></td>
                        <td class="alert"><?= $row['quantity'] ?></td>
                        <td><?= $row['low_stock_threshold'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <br>
        <button onclick="window.location.href='dashboard.php'" class="logout-button">⬅ Back to Dashboard</button>
    </div>
</body>
</html>
