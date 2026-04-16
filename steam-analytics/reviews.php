<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Submit a review
if (isset($_POST['game_id'], $_POST['rating'])) {
    $game_id = intval($_POST['game_id']);
    $rating = intval($_POST['rating']);
    $review_text = $conn->real_escape_string($_POST['review_text']);
    
    // Insert or update review
    $stmt = $conn->prepare("
        INSERT INTO reviews (game_id, user_id, rating, review_text) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE rating=?, review_text=?
    ");
    $stmt->bind_param("iiissi", $game_id, $user_id, $rating, $review_text, $rating, $review_text);
    $stmt->execute();
    $stmt->close();
}

// Fetch all reviews for a game (optional: filter by game)
$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : 0;
$review_result = $conn->query("
    SELECT r.*, u.name AS user_name
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    " . ($game_id ? "WHERE r.game_id = $game_id" : "") . "
    ORDER BY r.review_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Game Reviews</title>
</head>
<body>
<h2>📝 Reviews</h2>

<form method="post">
    <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
    Rating (1-10): <input type="number" name="rating" min="1" max="10" required>
    <br>
    Review: <textarea name="review_text"></textarea>
    <br>
    <button type="submit">Submit Review</button>
</form>

<ul>
<?php
if ($review_result->num_rows > 0) {
    while ($row = $review_result->fetch_assoc()) {
        echo "<li><strong>{$row['user_name']}</strong> ({$row['rating']}): {$row['review_text']}</li>";
    }
} else {
    echo "<li>No reviews yet</li>";
}
?>
</ul>

</body>
</html>