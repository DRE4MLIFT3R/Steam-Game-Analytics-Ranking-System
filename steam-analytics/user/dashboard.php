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

// Fetch user's favorite games
$fav_sql = "
    SELECT g.game_id, g.name, g.sale_percentage, g.crack_status
    FROM games g
    INNER JOIN user_favorites uf ON g.game_id = uf.game_id
    WHERE uf.user_id = ?
    ORDER BY g.name ASC
";
$stmt = $conn->prepare($fav_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$fav_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
body { min-height: 100vh; background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); color: #ffffff; }

/* Sidebar */
.sidebar { width: 220px; height: 100vh; background: rgba(27,40,56,0.95); backdrop-filter: blur(10px); position: fixed; padding-top: 30px; box-shadow: 2px 0 15px rgba(0,0,0,0.5); }
.sidebar h2 { text-align: center; margin-bottom: 30px; color: #66c0f4; font-weight: 600; text-shadow: 0 0 5px #66c0f4; }
.sidebar a { display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #66c0f4; text-decoration: none; border-radius: 8px; margin: 5px 10px; transition: 0.3s; font-weight: 500; }
.sidebar a i { font-size: 18px; }
.sidebar a:hover { background: rgba(102,192,244,0.15); color: #ffffff; text-shadow: 0 0 5px #66c0f4; transform: translateX(5px); }

/* Main content */
.main { margin-left: 220px; padding: 30px; }
.main h1 { font-size: 28px; font-weight: 600; margin-bottom: 10px; text-shadow: 0 0 5px #66c0f4; }
.main p { color: #c7d5e0; margin-bottom: 25px; }

/* Cards */
.card-container { display: flex; flex-wrap: wrap; gap: 18px; }
.card { background: rgba(27,40,56,0.8); backdrop-filter: blur(10px); padding: 18px; border-radius: 14px; width: 230px; box-shadow: 0 10px 25px rgba(0,0,0,0.4); transition: 0.3s ease; text-align: center; position: relative; }
.card img { width: 50px; margin-bottom: 12px; }
.card h3 { margin-top:0; color:#ffffff; font-weight:600; font-size:16px; }
.card p { color:#c7d5e0; margin:8px 0 15px 0; font-size:13px; }

/* Buttons */
.btn { display: inline-block; padding: 8px 14px; background: #66c0f4; color: #0f1923; text-decoration: none; border-radius: 6px; font-weight: 600; transition: 0.3s; font-size:13px; }
.btn:hover { background:#417a9b; transform: translateY(-2px); }

/* Responsive */
@media(max-width: 768px) { .main { margin-left:0; padding:20px; } .card-container { justify-content:center; } }

/* Favorites table */
.fav-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.fav-table th, .fav-table td { padding:10px; border-bottom:1px solid rgba(255,255,255,0.1); color:#fff; text-align:left; font-size:14px; }
.fav-table th { color:#c7d5e0; text-transform: uppercase; letter-spacing:1px; font-size:13px; }
.remove-fav { background:#ff5252; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; transition:0.3s; font-size:13px; }
.remove-fav:hover { opacity:0.8; text-decoration:none; }

</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="sidebar">
    <h2>Steam User</h2>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="view_games.php"><i class="fas fa-gamepad"></i> View Games</a>
    <a href="player_ranking.php"><i class="fas fa-chart-line"></i> Player Ranking</a>
    <a href="popularity_ranking.php"><i class="fas fa-trophy"></i> Popularity Ranking</a>
    <a href="../logout.php"><i class="fas fa-door-closed"></i> Logout</a>
</div>

<div class="main">
    <h1>Welcome, <?php echo $_SESSION['name']; ?> 👋</h1>
    <p>Explore game analytics and rankings.</p>

    <div class="card-container">
        <div class="card">
            <img src="https://img.icons8.com/ios-glyphs/90/66c0f4/game-controller.png" alt="Games">
            <h3>View All Games</h3>
            <p>Browse all available Steam games.</p>
            <a class="btn" href="view_games.php">Open</a>
        </div>
        <div class="card">
            <img src="https://img.icons8.com/ios-glyphs/90/66c0f4/combo-chart.png" alt="Player Ranking">
            <h3>Player Ranking</h3>
            <p>See games ranked by active players.</p>
            <a class="btn" href="player_ranking.php">Open</a>
        </div>
        <div class="card">
            <img src="https://img.icons8.com/ios-glyphs/90/66c0f4/trophy.png" alt="Popularity Ranking">
            <h3>Popularity Ranking</h3>
            <p>See games ranked by popularity score.</p>
            <a class="btn" href="popularity_ranking.php">Open</a>
        </div>
        <div class="card">
            <img src="https://img.icons8.com/ios-glyphs/90/66c0f4/plus-math.png" alt="Add Favorite">
            <h3>Add Favorite by Game ID</h3>
            <p>Directly add a game to your favorites using its Game ID.</p>
            <input type="number" id="fav-game-id" placeholder="Enter Game ID" style="margin-top:6px; padding:6px 10px; border-radius:4px; border:none; width:120px;">
            <button id="add-fav-btn" class="btn" style="margin-top:6px;">Add</button>
            <p id="fav-msg" style="margin-top:5px; font-size:13px;"></p>
        </div>
    </div>

    <h2 style="margin-top:35px;">⭐ Your Favorite Games</h2>
    <?php if($fav_result->num_rows > 0): ?>
        <table class="fav-table">
            <thead>
                <tr>
                    <th>Game Name</th>
                    <th>Crack Status</th>
                    <th>Sale %</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($fav = $fav_result->fetch_assoc()): 
                $statusClass = "unknown";
                if($fav['crack_status']==="Cracked") $statusClass="cracked";
                elseif($fav['crack_status']==="Uncracked") $statusClass="uncracked";
            ?>
                <tr>
                    <td><?php echo $fav['name']; ?></td>
                    <td class="<?php echo $statusClass; ?>"><?php echo $fav['crack_status']; ?></td>
                    <td><?php echo $fav['sale_percentage']; ?>%</td>
                    <td><button class="remove-fav" data-game-id="<?php echo $fav['game_id']; ?>">Remove</button></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color:#aaa;">You have no favorite games yet.</p>
    <?php endif; ?>
</div>

<script>
$(document).ready(function(){
    // Remove favorite
    $(".remove-fav").click(function(){
        var button = $(this);
        var game_id = button.data("game-id");
        $.post("user_favorites.php", { game_id: game_id }, function(response){
            location.reload();
        });
    });

    // Add favorite by Game ID
    $("#add-fav-btn").click(function(){
        var game_id = $("#fav-game-id").val().trim();
        if(game_id === "" || isNaN(game_id)){
            $("#fav-msg").text("Enter valid Game ID").css("color","#ffc107");
            return;
        }
        $.post("user_favorites.php", { game_id: game_id }, function(response){
            try{
                var res = JSON.parse(response);
                if(res.status==="added") $("#fav-msg").text(res.message).css("color","#4caf50");
                else if(res.status==="removed") $("#fav-msg").text(res.message).css("color","#ff5252");
                else $("#fav-msg").text(res.message).css("color","#ffc107");
                setTimeout(function(){ location.reload(); }, 1000);
            } catch(e){
                $("#fav-msg").text("Error processing request").css("color","#ff5252");
            }
        });
    });
});
</script>

</body>
</html>