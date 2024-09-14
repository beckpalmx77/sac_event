<?php
include 'config/connect_db.php';

$stmt = $conn->query("SELECT winner_lucky_draw,award_prize FROM evs_lucky_draw_award WHERE is_winner = 'N' ORDER BY id LIMIT 1");
$participant = $stmt->fetch();

echo json_encode($participant);
