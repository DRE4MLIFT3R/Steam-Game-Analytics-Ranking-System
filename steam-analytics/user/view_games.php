<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all games with crack status
$sql = "SELECT game_id, name, sale_percentage, crack_status FROM games ORDER BY name ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

// Fetch user's favorites
$fav_result = $conn->query("SELECT game_id FROM user_favorites WHERE user_id=$user_id");
$favorites = [];
while ($row = $fav_result->fetch_assoc()) {
    $favorites[] = $row['game_id'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Steam Analytics - View Games</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Poppins', sans-serif; }
        body {
            min-height:100vh;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display:flex; justify-content:center; align-items:center; padding:40px;
        }
        .container {
            width:100%; max-width:1100px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(15px);
            border-radius:16px; padding:40px;
            box-shadow:0 15px 40px rgba(0,0,0,0.4);
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }

        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
        .header h2 { font-size:28px; font-weight:600; color:#ffffff; }
        .back-btn {
            padding:10px 18px; border-radius:8px; background:#1b2838; color:#66c0f4;
            text-decoration:none; font-size:14px; transition:0.3s; border:1px solid #2a475e;
        }
        .back-btn:hover { background:#2a475e; transform:translateY(-2px); }

        table { width:100%; border-collapse:collapse; overflow:hidden; border-radius:12px; }
        thead { background: rgba(255,255,255,0.08); }
        th, td { padding:16px; font-size:14px; border-top:1px solid rgba(255,255,255,0.05); }
        th { text-align:left; font-weight:500; text-transform:uppercase; letter-spacing:1px; color:#c7d5e0; }
        td { color:#ffffff; }
        tbody tr { transition:0.3s ease; }
        tbody tr:hover { background: rgba(102,192,244,0.1); transform:scale(1.01); }
        .sale { text-align:right; font-weight:600; color:#4caf50; }
        .crack-status { font-weight:600; text-align:center; }
        .cracked { color:#ff5252; }
        .uncracked { color:#4caf50; }
        .unknown { color:#ffc107; }
        .empty { text-align:center; padding:20px; color:#aaa; }

        /* Favorite Button */
        .add-favorite, .favorited {
            background:none; border:1px solid #66c0f4; border-radius:8px; padding:5px 10px;
            color:#66c0f4; cursor:pointer; font-weight:600; transition:0.3s;
        }
        .add-favorite:hover { background: rgba(102,192,244,0.1); }
        .favorited { background:#66c0f4; color:#0f1923; }
        .favorited:hover { opacity:0.8; }

        @media(max-width:768px) {
            .container { padding:20px; }
            th, td { padding:12px; }
            .header h2 { font-size:22px; }
        }
    </style>

    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

<div class="container">

    <div class="header">
        <h2>🎮 Steam Games</h2>
        <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Game Name</th>
                <th>Crack Status</th>
                <th style="text-align:right;">Sale %</th>
                <th style="text-align:center;">Favorite</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statusClass = "unknown";
                if ($row['crack_status'] === "Cracked") $statusClass = "cracked";
                elseif ($row['crack_status'] === "Uncracked") $statusClass = "uncracked";

                $is_favorite = in_array($row['game_id'], $favorites);
                $fav_text = $is_favorite ? "★ Favorited" : "☆ Add to Favorites";
                $fav_class = $is_favorite ? "favorited" : "add-favorite";

                echo "<tr>
                        <td>{$row['name']}</td>
                        <td class='crack-status {$statusClass}'>{$row['crack_status']}</td>
                        <td class='sale'>{$row['sale_percentage']}%</td>
                        <td style='text-align:center;'>
                            <button class='{$fav_class}' data-game-id='{$row['game_id']}'>{$fav_text}</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='empty'>No games found</td></tr>";
        }
        ?>
        </tbody>
    </table>

</div>

<script>
$(document).ready(function() {
    $("button.add-favorite, button.favorited").click(function() {
        var button = $(this);
        var game_id = button.data("game-id");

        $.post("user_favorites.php", { game_id: game_id }, function(response) {
            // Toggle button
            if (button.hasClass("add-favorite")) {
                button.removeClass("add-favorite").addClass("favorited");
                button.text("★ Favorited");
            } else {
                button.removeClass("favorited").addClass("add-favorite");
                button.text("☆ Add to Favorites");
            }
        });
    });
});
</script>

</body>
</html>