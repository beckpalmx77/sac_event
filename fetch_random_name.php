<?php
$host = '192.168.88.7';
$db = 'sac_event';
$user = 'myadmin';
$pass = 'myadmin';
$port = '3307';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$stmt = $pdo->query("SELECT id FROM v_event_checkin WHERE is_winner = 'N' ORDER BY RAND() LIMIT 1");
$participant = $stmt->fetch();

echo json_encode($participant);
