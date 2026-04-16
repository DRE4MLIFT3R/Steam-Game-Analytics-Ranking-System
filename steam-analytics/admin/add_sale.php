<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../config/database.php";

// ===== ADMIN PROTECTION =====
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$message = "";

// ===== MANUAL SALE UPDATE =====
if(isset($_POST['update_sale'])){
    $appid = $_POST['game_id']; // steam_appid
    $sale = $_POST['sale_percentage'];

    $stmt = $conn->prepare("UPDATE games SET sale_percentage=? WHERE steam_appid=?");
    $stmt->bind_param("ii", $sale, $appid);
    if($stmt->execute()){
        $message = "Sale Updated Successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// ===== AUTO SALE UPDATE =====
if(isset($_POST['auto_update'])){
    $games_auto = $conn->query("SELECT steam_appid FROM games");
    if($games_auto){
        $updated = 0;
        while($game = $games_auto->fetch_assoc()){
            $appid = $game['steam_appid'];
            $url = "https://store.steampowered.com/api/appdetails?appids={$appid}&cc=us&filters=price_overview";

            // Use cURL instead of file_get_contents for reliability
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $json = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($httpcode === 200 && $json){
                $data = json_decode($json, true);
                if(isset($data[$appid]['data']['price_overview'])){
                    $discount = (int)$data[$appid]['data']['price_overview']['discount_percent'];

                    $stmt = $conn->prepare("UPDATE games SET sale_percentage=? WHERE steam_appid=?");
                    $stmt->bind_param("ii", $discount, $appid);
                    $stmt->execute();
                    $stmt->close();

                    $updated++;
                }
            }
        }
        $message = "Auto Update Completed! {$updated} games updated.";
    } else {
        $message = "No games found to auto-update.";
    }
}

// ===== FETCH GAMES FOR DROPDOWN =====
$games = $conn->query("SELECT steam_appid, name, sale_percentage FROM games ORDER BY name ASC");
if(!$games){
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Sales</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
body { min-height:100vh; background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); display:flex; justify-content:center; align-items:center; padding:20px; }
.container { width:100%; max-width:500px; background: rgba(255,255,255,0.05); backdrop-filter: blur(15px); padding:40px; border-radius:16px; box-shadow: 0 15px 40px rgba(0,0,0,0.4); animation: fadeIn 0.8s ease-in-out; }
@keyframes fadeIn { from { opacity:0; transform:translateY(20px);} to { opacity:1; transform:translateY(0);} }
h2 { text-align:center; color:#ffffff; margin-bottom:20px; font-weight:600; }
form select, form input { width:100%; padding:12px 15px; margin:10px 0; border:none; border-radius:12px; background: rgba(255,255,255,0.1); color:#fff; font-size:14px; transition:0.3s; }
form select:focus, form input:focus { background: rgba(102,192,244,0.1); outline:none; box-shadow: 0 0 10px rgba(102,192,244,0.3); }
button { width:100%; padding:12px; background:#66c0f4; border:none; border-radius:12px; font-weight:600; font-size:16px; cursor:pointer; color:black; transition:0.3s; margin-top:10px; }
button:hover { background:#417a9b; transform:translateY(-2px); }
.success { color:#4caf50; text-align:center; margin-bottom:10px; }
.back-btn { display:inline-block; margin-top:20px; text-decoration:none; color:#66c0f4; font-weight:500; transition:0.3s; }
.back-btn:hover { color:#417a9b; transform:translateY(-2px); }
@media(max-width:500px){ .container { padding:30px 20px; } }
</style>
</head>
<body>

<div class="container">
    <h2>💰 Update Game Sale</h2>

    <?php if($message) echo "<p class='success'>$message</p>"; ?>

    <form method="POST">
        <label>Select Game:</label>
        <select name="game_id" required>
            <?php while($row = $games->fetch_assoc()): ?>
                <option value="<?= $row['steam_appid']; ?>"><?= $row['name']; ?> - <?= $row['sale_percentage']; ?>% Off</option>
            <?php endwhile; ?>
        </select>

        <label>Sale Percentage:</label>
        <input type="number" name="sale_percentage" required>

        <button name="update_sale">Update Sale</button>
        <button name="auto_update" type="submit">Auto Update All Sales</button>
    </form>

    <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>