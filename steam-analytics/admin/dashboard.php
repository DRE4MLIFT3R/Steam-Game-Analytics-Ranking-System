<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

include "../config/database.php";

/* ===== ADMIN PROTECTION ===== */
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<!-- Google Font -->
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
    color: #ffffff;
    padding: 40px;
    display: flex;
    justify-content: flex-start;
}

/* Sidebar */
.sidebar {
    width: 220px;
    height: 100vh;
    background: rgba(27,40,56,0.95);
    backdrop-filter: blur(10px);
    position: fixed;
    padding-top: 30px;
    box-shadow: 0 0 15px rgba(0,0,0,0.5);
    border-radius: 0 16px 16px 0;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
    font-size: 22px;
    color: #66c0f4;
}

.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #c7d5e0;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
    border-radius: 8px;
    margin: 4px 10px;
}

.sidebar a:hover {
    background: rgba(102,192,244,0.1);
    color: #66c0f4;
    transform: translateX(2px);
}

/* Main content */
.main {
    margin-left: 240px;
    padding: 40px;
    width: calc(100% - 240px);
}

.main h1 {
    font-size: 28px;
    margin-bottom: 8px;
}

.main p {
    margin-bottom: 30px;
    color: #aaa;
}

/* Card layout */
.card-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    padding: 25px;
    border-radius: 16px;
    width: 250px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
}

.card h3 {
    margin-top: 0;
    margin-bottom: 8px;
    font-weight: 600;
}

.card p {
    font-size: 14px;
    color: #ccc;
    margin-bottom: 12px;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 8px 15px;
    background: #66c0f4;
    color: black;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
}

.btn:hover {
    background: #417a9b;
    transform: translateY(-2px);
}

/* Responsive */
@media(max-width: 768px){
    .sidebar {
        width: 180px;
        padding-top: 20px;
    }

    .main {
        margin-left: 200px;
        padding: 20px;
    }

    .card {
        width: 100%;
    }
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>Steam Admin</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="add_game.php">➕ Add Game</a>
    <a href="add_sale.php">💰 Add Sale</a>
    <a href="fetch_players.php">📈 Update Players</a>
    <a href="calculate_ranking.php">🏆 Update Rankings</a>
    <a href="view_players.php">📊 View Player Count</a>
    <a href="view_ranking.php">🏅 View Rankings</a>
    <a href="view_popularity.php">📈 Popularity Breakdown</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<div class="main">
    <h1>Welcome, <?php echo $_SESSION['name']; ?> 👋</h1>
    <p>Admin Control Panel</p>

    <div class="card-container">

        <div class="card">
            <h3>Add New Game</h3>
            <p>Insert a new Steam game into database.</p>
            <a class="btn" href="add_game.php">Open</a>
        </div>

        <div class="card">
            <h3>Manage Sales</h3>
            <p>Add or update game discounts.</p>
            <a class="btn" href="add_sale.php">Open</a>
        </div>

        <div class="card">
            <h3>Fetch Player Data</h3>
            <p>Update real-time Steam player count.</p>
            <a class="btn" href="fetch_players.php">Run</a>
        </div>

        <div class="card">
            <h3>Calculate Rankings</h3>
            <p>Recalculate popularity score.</p>
            <a class="btn" href="calculate_ranking.php">Run</a>
        </div>

        <div class="card">
            <h3>View Player Count</h3>
            <p>See games ranked by active players.</p>
            <a class="btn" href="view_players.php">Open</a>
        </div>

        <div class="card">
            <h3>View Rankings</h3>
            <p>See games ranked by popularity score.</p>
            <a class="btn" href="view_ranking.php">Open</a>
        </div>

        <div class="card">
            <h3>Popularity Breakdown</h3>
            <p>See full detailed popularity stats.</p>
            <a class="btn" href="view_popularity.php">Open</a>
        </div>

    </div>
</div>

</body>
</html>