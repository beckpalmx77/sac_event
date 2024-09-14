<?php
include 'config/connect_db.php';

$stmt = $conn->query("SELECT id FROM v_event_checkin WHERE is_winner = 'N' ORDER BY RAND() LIMIT 1");
$participant = $stmt->fetch();

echo json_encode($participant);
