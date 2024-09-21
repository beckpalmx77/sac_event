<?php
// เชื่อมต่อฐานข้อมูล
//$conn = new PDO('mysql:host=192.168.88.7;port=3307;dbname=sac_event;charset=utf8', 'myadmin', 'myadmin');
include 'config\connect_db.php';

// ดึงข้อมูล ชื่อ, นามสกุล, เบอร์โทร ของผู้เข้าร่วมที่ทำการ Check-In

$sql_chk = "SELECT 
    evs_customer.ar_name, 
    v_event_checkin.table_number, 
    evs_customer.province_name, 
    CASE 
        WHEN v_event_checkin.check_in_status = 'Y' THEN 'Yes' 
        ELSE 'NO' 
    END AS check_in_status_text,
    v_event_checkin.update_chk_in_date, 
    v_event_checkin.order_record, 
    evs_customer.group_guest
FROM 
    v_event_checkin 
LEFT JOIN 
    evs_customer 
ON 
    evs_customer.cust_id = v_event_checkin.cust_id
WHERE 
    v_event_checkin.check_in_status = 'N' 
ORDER BY 
    v_event_checkin.table_number ";

$stmt = $conn->prepare($sql_chk);
$stmt->execute();

// เก็บข้อมูลในรูปแบบ JSON
$attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($attendees);

