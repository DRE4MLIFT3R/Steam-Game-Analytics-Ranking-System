<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config/database.php';

/* ===== ADMIN PROTECTION ===== */
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM games ORDER BY player_count DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Games</title>

    <style>
        body{
            margin:0;
            font-family:Arial;
            background:#0f1923;
            color:white;
            padding:40px;
        }

        h2{
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:#1b2838;
        }

        th, td{
            padding:12px;
            border-bottom:1px solid #2a475e;
            text-align:left;
        }

        th{
            background:#2a475e;
        }

        tr:hover{
            background:#22394b;
        }

        .btn{
            display:inline-block;
            margin-bottom:20px;
            padding:8px 15px;
            background:#66c0f4;
            color:black;
            text-decoration:none;
            border-radius:5px;
            font-weight:bold;
        }

        .btn:hover{
            background:#417a9b;
        }

        .empty{
            padding:20px;
            background:#1b2838;
            border-radius:8px;
        }
    </style>
</head>
<body>

<a class="btn" href="dashboard.php">⬅ Back to Dashboard</a>

<h2>🏆 All Games (Ranked by Player Count)</h2>

<?php if($result && $result->num_rows > 0): ?>

<table>
<tr>
    <th>Rank</th>
    <th>Name</th>
    <th>Players</th>
    <th>Price</th>
</tr>

<?php
$rank = 1;
while($row = $result->fetch_assoc()){
    echo "<tr>
            <td>$rank</td>
            <td>{$row['name']}</td>
            <td>{$row['player_count']}</td>
            <td>₹{$row['price']}</td>
          </tr>";
    $rank++;
}
?>

</table>

<?php else: ?>

<div class="empty">
    No games found in database.
</div>

<?php endif; ?>

</body>
</html>