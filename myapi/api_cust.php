<?php
include '../config/connect_db.php';
header("Content-Type: application/json");

$ar_name = isset($_GET['ar_name']) ? $_GET['ar_name'] : '';
$phone = isset($_GET['phone']) ? $_GET['phone'] : '';

try {
    $sql = 'SELECT * FROM v_event_checkin WHERE 1=1';
    if ($ar_name) {
        $sql .= ' AND ar_name LIKE :ar_name';
    }
    if ($phone) {
        $sql .= ' AND phone LIKE :phone';
    }
    $stmt = $conn->prepare($sql);
    if ($ar_name) {
        $stmt->bindValue(':ar_name', "%$ar_name%");
    }
    if ($phone) {
        $stmt->bindValue(':phone', "%$phone%");
    }
    $stmt->execute();
    $v_event_checkin = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($v_event_checkin);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}
