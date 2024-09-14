<?php

include 'config/connect_db.php';

$sql_get = "SELECT award_prize FROM evs_lucky_draw_award WHERE is_winner = 'N' ORDER BY id LIMIT 1";
$stmt = $conn->query($sql_get);
$prizes = $stmt->fetch();

echo json_encode($prizes);