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

// Fetch products
$sql = "SELECT * FROM product ORDER BY idproduct ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
    <style>
    /*

        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        tr.low-stock {
            background-color: #ffe5e5;
        }
        a.button {
            display: block;
            width: 100px;
            margin: 20px auto;
            text-align: center;
            background-color: #4285f4;
            color: white;
            padding: 8px;
            text-decoration: none;
            border-radius: 5px;
        }
        a.button:hover {
            background-color: #2a63c4;
        }
        */
    </style>
</head>
<body>

<h2>Clothing Inventory</h2>

<table>
    <tr>

        <!-- table headers -->
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
    </tr>

    <!-- check if products exist -->
    <?php if ($result && $result->num_rows > 0): ?>

    <!-- loop throough each product -->
        <?php while ($row = $result->fetch_assoc()): ?>

    <!-- highlight row if  stock quantity is below the threshold -->
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
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="11">No products found.</td></tr>
    <?php endif; ?>
</table>

<a class="button" href="dashboard.html">Back</a>

</body>
</html>
