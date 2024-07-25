<?php
include '../config/connect_db.php';
header("Content-Type: application/json");


$name = isset($_GET['ar_name']) ? $_GET['ar_name'] : '';
$email = isset($_GET['phone']) ? $_GET['phone'] : '';

try {
    $sql = 'SELECT * FROM v_event_checkin WHERE 1=1';

    if ($name) {
        $sql .= ' AND ar_name LIKE :ar_name';
    }
    if ($email) {
        $sql .= ' AND phone LIKE :phone';
    }
    $stmt = $conn->prepare($sql);
    if ($name) {
        $stmt->bindValue(':ar_name', "%$ar_name%");
    }
    if ($email) {
        $stmt->bindValue(':phone', "%$phone%");
    }
    $stmt->execute();
    $customers = $stmt->fetchAll();
    echo json_encode($customers);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}


