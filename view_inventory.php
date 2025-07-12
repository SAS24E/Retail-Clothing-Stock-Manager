<?php
session_start();
require_once 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check login session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Determine if user is admin or employee
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$back_link = $is_admin ? 'dashboard.php' : 'employee_dashboard.php';

// Fetch products
if ($is_admin) {
    // Admin sees all products
    $sql = "SELECT * FROM product ORDER BY idproduct ASC";
} else {
    // Employees only see approved products
    $sql = "SELECT * FROM product WHERE status = 'approved' ORDER BY idproduct ASC";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Inventory</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <h2>Clothing Inventory</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Size</th>
            <th>Color</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Low Stock Threshold</th>
            <th>Created</th>
            <th>Updated</th>
            <?php if ($is_admin): ?>
                <th>Status</th>
            <?php endif; ?>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="<?= ($row['quantity'] <= $row['low_stock_threshold']) ? 'low-stock' : '' ?>">
                    <td><?= $row['idproduct'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['brand'] ?></td>
                    <td><?= $row['category'] ?></td>
                    <td><?= $row['size'] ?></td>
                    <td><?= $row['color'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['low_stock_threshold'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['updated_at'] ?></td>
                    <?php if ($is_admin): ?>
                        <td><?= $row['status'] ?></td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= $is_admin ? '12' : '11' ?>">No products found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <div style="text-align: center; margin-top: 30px;">
        <button onclick="window.location.href='../dashboard.php'" class="logout-button">Back to Dashboard</button>
    </div>

</body>

</html>