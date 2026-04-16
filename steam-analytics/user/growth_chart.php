<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
include "../config/database.php";

$game_id = 1;

$query = "
SELECT recorded_at, current_players 
FROM player_stats 
WHERE game_id=$game_id
ORDER BY recorded_at ASC
";

$result = mysqli_query($conn, $query);

$dates = [];
$players = [];

while($row = mysqli_fetch_assoc($result)){
    $dates[] = $row['recorded_at'];
    $players[] = $row['current_players'];
}
?>

<canvas id="growthChart"></canvas>

<script>
const ctx = document.getElementById('growthChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'Player Growth',
            data: <?php echo json_encode($players); ?>,
            borderColor: '#66c0f4',
            fill: false
        }]
    }
});
</script>