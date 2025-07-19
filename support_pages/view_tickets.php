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
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$back_link = $is_admin ? '../dashboard.php' : '../employee_dashboard.php';

// Handle respond/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    $ticket_id = intval($_POST['ticket_id']);
    $action = $_POST['action'] ?? '';

    if ($action === 'respond') {
        $response = trim($_POST['response']);
        $stmt = $conn->prepare("UPDATE support_tickets SET response = ?, status = 'responded' WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $response, $ticket_id);
            if (!$stmt->execute()) {
                die("Error executing response update: " . $stmt->error);
            }
        } else {
            die("Error preparing response update: " . $conn->error);
        }
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM support_tickets WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $ticket_id);
            if (!$stmt->execute()) {
                die("Error executing delete: " . $stmt->error);
            }
        } else {
            die("Error preparing delete: " . $conn->error);
        }
    }

    header("Location: view_tickets.php?success=1");
    exit;
}

// Load tickets
if ($is_admin) {
    $sql = "SELECT * FROM support_tickets ORDER BY created_at DESC";
    $tickets = $conn->query($sql);
} else {
    $sql = "SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $tickets = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Support Tickets</title>
    <link rel="stylesheet" href="../styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class=" tickets-wrapper">
    <h2><?= $is_admin ? 'All' : 'My' ?> Support Tickets</h2>
    <?php if (isset($_GET['success'])): ?>
        <p class="success">Action completed successfully.</p>
    <?php endif; ?>

    <?php if ($tickets && $tickets->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Status</th>
                <th>Response</th>
                <th>Created At</th>
                <?php if ($is_admin): ?><th>Actions</th><?php endif; ?>
            </tr>
            <?php while ($ticket = $tickets->fetch_assoc()): ?>
                <tr>
                    <td><?= $ticket['id'] ?></td>
                    <td><?= htmlspecialchars($ticket['name']) ?></td>
                    <td><?= htmlspecialchars($ticket['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($ticket['message'])) ?></td>
                    <td><?= htmlspecialchars($ticket['status']) ?></td>
                    <td><?= nl2br(htmlspecialchars($ticket['response'] ?? '')) ?></td>
                    <td><?= $ticket['created_at'] ?></td>
                    <?php if ($is_admin): ?>
                        <td>
                            <form method="post" class="inline-form">
                                <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                <textarea name="response" placeholder="Enter response here..."><?= htmlspecialchars($ticket['response'] ?? '') ?></textarea>
                                <button type="submit" name="action" value="respond" class="respond-btn">Respond</button>
                                <button type="submit" name="action" value="delete" class="delete-btn" onclick="return confirm('Delete this ticket?');">Delete</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No support tickets found.</p>
    <?php endif; ?>


    <div style="text-align: center; margin-top: 30px;">
        <button class="hover" onclick="window.location.href='<?= $back_link ?>'"> Back to Dashboard</button>
    </div>




    </div>
    </body>

</html>