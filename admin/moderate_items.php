<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}

$result = $conn->query("SELECT * FROM product WHERE status = 'pending' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Moderate Inventory Items</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>

    <h2 style="text-align:center;">Moderate Inventory Items</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['idproduct'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['brand']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>$<?= number_format($row['price'], 2) ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <?php if ($row['status'] === 'pending'): ?>
                        <a class="approve" href="approve_item.php?id=<?= $row['idproduct'] ?>">Approve</a>
                        <a class="reject" href="reject_item.php?id=<?= $row['idproduct'] ?>">Reject</a>
                    <?php else: ?>
                        <em>No actions</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div style="text-align: center; margin-top: 30px;">
        <button onclick="window.location.href='../dashboard.php'" class="logout-button">Back to Dashboard</button>
    </div>


</body>

</html>