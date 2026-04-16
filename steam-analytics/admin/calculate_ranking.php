<?php
include "../config/database.php";

$query = "SELECT game_id, player_count, sale_percentage FROM games";
$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Failed: " . mysqli_error($conn));
}

while($row = mysqli_fetch_assoc($result)){

    $game_id = $row['game_id'];
    $players = $row['player_count'] ?? 0;
    $sale = $row['sale_percentage'] ?? 0;

    // Popularity Formula
    $popularity = ($players * 0.7) + ($sale * 0.3);

    $update = $conn->prepare("
        UPDATE games
        SET popularity_score = ?
        WHERE game_id = ?
    ");

    $update->bind_param("di", $popularity, $game_id);
    $update->execute();
    $update->close();
}

echo "Ranking Calculated Successfully!";
?>