<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    $stmt = $conn->prepare("INSERT INTO support_tickets (user_id, name, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $name, $email, $message);

    if ($stmt->execute()) {
        header("Location: view_tickets.php?success=1");
        exit;
    } else {
        $error = "Failed to submit ticket. " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Submit Support Ticket</title>
    <link rel="stylesheet" href="../styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class=" tickets-wrapper">
    <h2>Submit a Support Ticket</h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form class="support-form" method="post" action="submit_ticket.php">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Describe the issue..." required></textarea>
        <button type="submit">Submit Ticket</button>
    </form>

    <div style="margin-top: 20px;">
        <a href="view_tickets.php">View My Tickets</a>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="../<?= $_SESSION['role'] === 'admin' ? 'dashboard.php' : 'employee_dashboard.php' ?>">
            <button class="logout">Back to Dashboard</button>
        </a>
    </div>
    </div>
    </body>

</html>