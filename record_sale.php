<?php
session_start();
require_once 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only allow logged-in users
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header("Location: index.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all available products
$products = $conn->query("SELECT idproduct, name, quantity FROM product WHERE status = 'approved' ORDER BY name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity_sold = intval($_POST['quantity_sold']);

    // Fetch current stock
    $result = $conn->query("SELECT quantity FROM product WHERE idproduct = $product_id");
    $row = $result->fetch_assoc();

    if ($row && $row['quantity'] >= $quantity_sold && $quantity_sold > 0) {
        // Record the sale
        $stmt = $conn->prepare("INSERT INTO sales_log (quantity, sold_at, user_iduser, product_idproduct) VALUES (?, NOW(), ?, ?)");
        $stmt->bind_param("iii", $quantity_sold, $user_id, $product_id);
        $stmt->execute();

        // Update product inventory
        $conn->query("UPDATE product SET quantity = quantity - $quantity_sold WHERE idproduct = $product_id");

        $success = "Sale recorded successfully!";
    } else {
        $error = "Invalid quantity: not enough stock or input error.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Sale</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-wrapper {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        label, select, input {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
        }
        .success { color: green; text-align: center; font-weight: bold; }
        .error { color: red; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-wrapper">
        <h2>Record Sale</h2>

        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" required>
                <option value="">Select a product</option>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <option value="<?= $product['idproduct'] ?>">
                        <?= htmlspecialchars($product['name']) ?> (In stock: <?= $product['quantity'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="quantity_sold">Quantity Sold</label>
            <input type="number" name="quantity_sold" id="quantity_sold" min="1" required>

            <button type="submit">Submit Sale</button>
            <button type="button" onclick="window.location.href='<?= $_SESSION['role'] === 'admin' ? 'dashboard.php' : 'employee_dashboard.php' ?>'">Back</button>
        </form>
    </div>
</body>
</html>
