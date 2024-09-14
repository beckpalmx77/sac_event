<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ar_name = $_POST['ar_name'];
    $phone = $_POST['phone'];
    $table_number = $_POST['table_number'];

    // คุณสามารถเพิ่มการจัดการข้อมูลเพิ่มเติมที่นี่ เช่น การบันทึกข้อมูลลงฐานข้อมูล
    $data = [
        'ar_name' => htmlspecialchars($ar_name),
        'phone' => htmlspecialchars($phone),
        'table_number' => htmlspecialchars($table_number)
    ];

    echo json_encode($data);
}

