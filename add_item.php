<?php
session_start();
require_once 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in (admin or employee)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header("Location: index.html");
    exit;
}

$is_admin = $_SESSION['role'] === 'admin';
$back_link = $is_admin ? 'dashboard.php' : 'employee_dashboard.php';

// Determine item status
$status = $is_admin ? 'approved' : 'pending';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $price = floatval(str_replace(['$', ','], '', $_POST['price']));
    $quantity = intval($_POST['quantity']);
    $threshold = intval($_POST['threshold']);

    $stmt = $conn->prepare("INSERT INTO product (name, brand, category, size, color, price, quantity, low_stock_threshold, created_at, updated_at, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
    $stmt->bind_param("sssssdiss", $name, $brand, $category, $size, $color, $price, $quantity, $threshold, $status);

    if ($stmt->execute()) {
        $success = $is_admin 
            ? "Item added and approved successfully!" 
            : "Item submitted for approval!";
    } else {
        $error = "Error adding item: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Item</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Add New Inventory Item</h2>

    <?php if (isset($success)): ?>
        <p class="success"><?= $success ?></p>
    <?php elseif (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="brand">Brand:</label>
            <input type="text" id="brand" name="brand" required>
        </div>

        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="Shirts">Shirts</option>
                <option value="Pants">Pants</option>
                <option value="Footwear">Footwear</option>
                <option value="Outerwear">Outerwear</option>
            </select>
        </div>

        <div class="form-group">
            <label for="size">Size:</label>
            <input type="text" id="size" name="size" required>
        </div>

        <div class="form-group">
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" required>
        </div>

        <div class="form-group">
            <label for="price">Price ($):</label>
            <input type="text" id="price" name="price" required pattern="^\$?\d+(\.\d{2})?$" title="Format: $XX.XX">
        </div>

        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="0" required>
        </div>

        <div class="form-group">
            <label for="threshold">Low Stock Threshold:</label>
            <input type="number" id="threshold" name="threshold" min="0" required>
        </div>

        <button type="submit">Add Item</button>
        <button type="button" onclick="window.location.href='<?= $back_link ?>'">Back</button>
    </form>
</body>
</html>
