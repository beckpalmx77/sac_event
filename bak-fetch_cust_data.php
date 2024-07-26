<?php
$conn = new PDO("mysql:host=192.168.88.7;dbname=sac_event;charset=utf8;port=3307", "myadmin", "myadmin");

$stmt = $conn->prepare("SELECT cust_id,ar_name,cust_name_1,phone FROM evs_customer");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["data" => $rows]);

