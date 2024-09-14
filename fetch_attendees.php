<?php
// เชื่อมต่อฐานข้อมูล
//$conn = new PDO('mysql:host=192.168.88.7;port=3307;dbname=sac_event;charset=utf8', 'myadmin', 'myadmin');
include 'config\connect_db.php';

// ดึงข้อมูล ชื่อ, นามสกุล, เบอร์โทร ของผู้เข้าร่วมที่ทำการ Check-In

$sql_chk = "SELECT ar_name, table_number , province_name 
, CASE WHEN check_in_status = 'Y' THEN 'Yes' ELSE 'NO' END AS check_in_status_text
, update_chk_in_date , order_record 
FROM v_event_checkin WHERE check_in_status = 'Y' 
ORDER BY update_chk_in_date ";

$stmt = $conn->prepare($sql_chk);
$stmt->execute();

// เก็บข้อมูลในรูปแบบ JSON
$attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($attendees);

