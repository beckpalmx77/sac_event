// mark_winner.php
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

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];

$stmt = $pdo->prepare("UPDATE evs_event_checkin SET is_winner = 'Y' WHERE name = ?");
$stmt->execute([$name]);

?>