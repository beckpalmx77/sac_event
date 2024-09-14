<?php
include 'config/connect_db.php';

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];

$stmt = $conn->prepare("SELECT * FROM v_event_checkin WHERE id = ?");
$stmt->execute([$name]);
$participant = $stmt->fetch();

echo json_encode($participant);

