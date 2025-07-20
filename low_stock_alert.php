<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in (admin or employee)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header("Location: index.html");
    exit;
}

$is_admin = $_SESSION['role'] === 'admin';
$back_link = $is_admin ? 'dashboard.php' : 'employee_dashboard.php';

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
    <link rel="stylesheet" href=styles.css>
</head>

<body>
    <div class="container">
        <h2> Low Stock Alerts</h2>

        <?php if ($low_stock_query->num_rows === 0): ?>
            <p style="color: green;">All stock levels are sufficient </p>
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
        <button type="button" onclick="window.location.href='<?= $back_link ?>'" class="back-button">Back</button>
    </div>
</body>

</html>