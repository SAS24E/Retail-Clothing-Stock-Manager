<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit;
}

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$back_link = $is_admin ? '../dashboard.php' : '../employee_dashboard.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Support Ticket</title>
    <link rel="stylesheet" href="../styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="form-wrapper">
        <h2>Submit a Support Ticket</h2>

        <?php if (isset($_GET['success'])): ?>
            <p class="success">Ticket submitted successfully.</p>
        <?php endif; ?>

        <form action="submit_ticket.php" method="post">
            <div class="form-group">
                <input type="text" name="name" placeholder="Your Name" required>
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Your Email" required>
            </div>

            <div class="form-group">
                <textarea name="message" placeholder="Describe the issue..." rows="6" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit">Submit Ticket</button>
            </div>
        </form>

        <div class="form-actions">
            <a href="view_tickets.php" class="button">View My Tickets</a>
        </div>

        <div class="form-actions">
            <button class="logout" onclick="window.location.href='<?= $back_link ?>'">Back to Dashboard</button>
        </div>
    </div>
</body>

</html>