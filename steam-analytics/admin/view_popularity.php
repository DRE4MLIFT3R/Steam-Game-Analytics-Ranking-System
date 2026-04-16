<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("
    SELECT * 
    FROM games 
    ORDER BY popularity_score DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Popularity Details</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.container {
    width: 100%;
    max-width: 1000px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from { opacity:0; transform:translateY(20px);}
    to { opacity:1; transform:translateY(0);}
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header h2 {
    font-size: 28px;
    font-weight: 600;
    color: #ffffff;
}

.back-btn {
    padding: 10px 18px;
    border-radius: 8px;
    background: #1b2838;
    color: #66c0f4;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
    border: 1px solid #2a475e;
}

.back-btn:hover {
    background: #2a475e;
    transform: translateY(-2px);
}

table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 12px;
}

thead {
    background: rgba(255,255,255,0.08);
}

th, td {
    padding: 16px;
    font-size: 14px;
    color: #ffffff;
    border-top: 1px solid rgba(255,255,255,0.05);
}

th {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

tbody tr {
    transition: 0.3s ease;
}

tbody tr:hover {
    background: rgba(102,192,244,0.1);
    transform: scale(1.01);
}

/* Column alignment */
td:nth-child(1) { text-align:center; width:70px; }
td:nth-child(2) { text-align:left; }
td:nth-child(3) { text-align:right; }
td:nth-child(4) { text-align:center; }
td:nth-child(5) { text-align:right; font-weight:600; padding-right:20px; }

.empty {
    text-align: center;
    padding: 20px;
    color: #aaa;
}

@media(max-width:768px){
    .container { padding: 20px; }
    .header h2 { font-size: 22px; }
    th, td { padding: 12px; }
}
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>📊 Full Popularity Breakdown</h2>
        <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Game</th>
                <th>Players</th>
                <th>Sale %</th>
                <th>Popularity Score</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $rank = 1;
        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$rank}</td>
                        <td>{$row['name']}</td>
                        <td>" . number_format($row['player_count']) . "</td>
                        <td>{$row['sale_percentage']}%</td>
                        <td>" . number_format($row['popularity_score']) . "</td>
                      </tr>";
                $rank++;
            }
        } else {
            echo "<tr><td colspan='5' class='empty'>No games found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>