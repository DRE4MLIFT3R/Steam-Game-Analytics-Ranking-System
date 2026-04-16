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

/* ===== FORM SUBMIT ===== */
if(isset($_POST['add_game'])){

    $appid      = (int)$_POST['steam_appid'];
    $name       = trim($_POST['name']);
    $developer  = trim($_POST['developer']);
    $release    = $_POST['release_date'];
    $price      = (float)$_POST['base_price'];
    $rating     = (float)$_POST['rating'];
    $crack      = $_POST['crack_status']; // NEW FIELD

    $stmt = $conn->prepare("
        INSERT INTO games 
        (steam_appid, name, developer, release_date, base_price, rating, crack_status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    if(!$stmt){
        die("Prepare Failed: " . $conn->error);
    }

    $stmt->bind_param("isssdss", 
        $appid, 
        $name, 
        $developer, 
        $release, 
        $price, 
        $rating,
        $crack
    );

    if($stmt->execute()){
        $success = "Game Added Successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Game</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding: 20px;
    color: #fff;
}

/* Form container */
.box {
    background: rgba(27,40,56,0.6);
    backdrop-filter: blur(15px);
    padding: 40px 30px;
    border-radius: 20px;
    width: 400px;
    max-width: 100%;
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.box h2 {
    margin-bottom: 20px;
    color: #66c0f4;
    text-align: center;
}

/* Inputs and select */
input, select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 10px;
    border: none;
    outline: none;
    background: rgba(255,255,255,0.05);
    color: #fff;
    font-size: 14px;
    transition: 0.3s;
}

input:focus, select:focus {
    background: rgba(102,192,244,0.1);
}

/* Button */
button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    background: #66c0f4;
    color: #0f1923;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #417a9b;
    transform: translateY(-2px);
}

/* Messages */
.success {
    color: #4caf50;
    text-align: center;
    margin-bottom: 10px;
}

.error {
    color: #ff5252;
    text-align: center;
    margin-bottom: 10px;
}

/* Back link */
.box a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #66c0f4;
    text-decoration: none;
    transition: 0.3s;
}

.box a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="box">
    <h2>Add New Game</h2>

    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="number" name="steam_appid" placeholder="Steam App ID (e.g., 730)" required>
        <input type="text" name="name" placeholder="Game Name" required>
        <input type="text" name="developer" placeholder="Developer" required>
        <input type="date" name="release_date" required>
        <input type="number" step="0.01" name="base_price" placeholder="Base Price" required>
        <input type="number" step="0.1" name="rating" placeholder="Rating (0-10)" required>

        <select name="crack_status" required>
            <option value="">Select Crack Status</option>
            <option value="Cracked">Cracked</option>
            <option value="Not Cracked">Not Cracked</option>
            <option value="Unknown">Unknown</option>
        </select>

        <button type="submit" name="add_game">Add Game</button>
    </form>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>