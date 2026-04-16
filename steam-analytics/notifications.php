<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Mark all notifications as read
if (isset($_POST['mark_read'])) {
    $conn->query("UPDATE notifications SET is_read = 1 WHERE user_id = $user_id");
}

// Fetch notifications
$result = $conn->query("
    SELECT n.notification_id, g.name AS game_name, n.message, n.is_read, n.created_at
    FROM notifications n
    JOIN games g ON n.game_id = g.game_id
    WHERE n.user_id = $user_id
    ORDER BY n.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
</head>
<body>
<h2>🔔 Notifications</h2>
<form method="post">
    <button name="mark_read">Mark All as Read</button>
</form>
<ul>
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $style = $row['is_read'] ? "opacity:0.6;" : "font-weight:bold;";
        echo "<li style='$style'>{$row['created_at']}: {$row['message']} ({$row['game_name']})</li>";
    }
} else {
    echo "<li>No notifications</li>";
}
?>
</ul>
</body>
</html>