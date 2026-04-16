\<?php
include "../config/database.php";

$query = "SELECT game_id, steam_appid FROM games";
$result = mysqli_query($conn, $query);

while($game = mysqli_fetch_assoc($result)){

    $appid = $game['steam_appid'];
    $game_id = $game['game_id'];

    $url = "https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1/?appid=$appid";

    $response = file_get_contents($url);

    if($response === FALSE){
        continue;
    }

    $data = json_decode($response, true);

    if(isset($data['response']['player_count'])){
        $players = $data['response']['player_count'];

        $stmt = $conn->prepare("
            UPDATE games 
            SET player_count = ?
            WHERE game_id = ?
        ");

        $stmt->bind_param("ii", $players, $game_id);
        $stmt->execute();
        $stmt->close();
    }
}

echo "Player Data Updated Successfully!";
?>