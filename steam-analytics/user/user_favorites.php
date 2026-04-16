<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../config/database.php";

header('Content-Type: application/json'); // <-- ensures JSON response

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['game_id'])) {
    $game_id = intval($_POST['game_id']);

    // Check if game exists
    $stmt = $conn->prepare("SELECT * FROM games WHERE game_id=?");
    $stmt->bind_param("i",$game_id);
    $stmt->execute();
    $game_res = $stmt->get_result();

    if($game_res->num_rows === 0){
        echo json_encode(["status"=>"error","message"=>"Game ID not found"]);
        exit();
    }

    // Check if already favorited
    $stmt = $conn->prepare("SELECT * FROM user_favorites WHERE user_id=? AND game_id=?");
    $stmt->bind_param("ii",$user_id,$game_id);
    $stmt->execute();
    $check_res = $stmt->get_result();

    if($check_res->num_rows > 0){
        // Remove from favorites
        $stmt = $conn->prepare("DELETE FROM user_favorites WHERE user_id=? AND game_id=?");
        $stmt->bind_param("ii",$user_id,$game_id);
        $stmt->execute();
        echo json_encode(["status"=>"removed","message"=>"Removed from favorites"]);
    } else {
        // Add to favorites
        $stmt = $conn->prepare("INSERT INTO user_favorites (user_id, game_id) VALUES (?,?)");
        $stmt->bind_param("ii",$user_id,$game_id);
        $stmt->execute();
        echo json_encode(["status"=>"added","message"=>"Added to favorites"]);
    }
    exit();
}
echo json_encode(["status"=>"error","message"=>"No Game ID provided"]);
?>