<?php

ini_set('display_errors', 1);
error_reporting(~0);

include("../config/connect_db.php");
include('../util/send_line_msg.php');

// ตั้งค่าการเข้ารหัสเป็น utf8
$conn->exec("SET NAMES 'utf8'");
$conn->exec("SET CHARACTER SET utf8");

// กำหนดการแสดงผลเป็น UTF-8
header('Content-Type: text/html; charset=utf-8');

$table = "v_event_checkin"; // ใช้ตัวแปรที่ถูกต้อง

$total_register = 0;
$attendance_qty = 0;

for ($loop = 1; $loop <= 2; $loop++) {
    if ($loop == 1) {
        $field = "register_qty";
        $cond = ""; // เงื่อนไขว่างในครั้งแรก
    } else {
        $field = "attendance_qty";
        $cond = " WHERE check_in_status = 'Y'"; // เงื่อนไขสำหรับ check_in_status
    }

    // ใช้ table ที่ถูกต้อง
    $sql_get = "SELECT SUM($field) AS sum_filed FROM $table $cond";

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        if ($loop == 1) {
            $total_register = $result['sum_filed'];
        } else {
            $attendance_qty = $result['sum_filed'];
        }
    }
}

// แสดงผลลัพธ์เป็นภาษาไทย
$str1 = "จำนวนผู้ลงทะเบียน : " . ($total_register) . " ท่าน " . "\n\r";
$str2 = "จำนวนผู้เข้าร่วมงาน Check In : " . ($attendance_qty) . " ท่าน " . "\n\r";


// คำนวณค่ารวม
$sum_check_in_total = $total_register - $attendance_qty;
$str3 = "ยังไม่ได้ Check In : " . $sum_check_in_total . " ท่าน " . "\n\r";

// คำนวณเปอร์เซ็นต์
if ($total_register > 0) {
    $percent_chk = ($attendance_qty * 100) / $total_register;
    $str4 = "เปอร์เซ็นต์การ Check-in : " . number_format($percent_chk, 2) . "%" . "\n\r";
} else {
    $str4 = "ไม่สามารถคำนวณเปอร์เซ็นต์ได้ เนื่องจากไม่มีผู้ลงทะเบียน" . "\n\r";
}

$msg = $str1 . $str2 . $str3 . $str4;
$access_token = 'Shw8xgMW5E9qSgkqGUykrY+YZLAT+PcaM2pdutHSloNWDPMPqjbfrHUycRoM7txPoGIgVi6rV+7NgZxp3nmtCn6mnazWJCbk/I0++o+JRr/j8HP4qSxCksI1E9LlvVozjmywwOS/gqz8maqOcXrofwdB04t89/1O/w1cDnyilFU=';

if ($attendance_qty > 0) {
    $sql_sale_line = "SELECT esn.sale_line_user_id AS data,esn.sale_name_desc FROM evs_sale_name esn";
    $stmt = $conn->prepare($sql_sale_line);
    $stmt->execute();
// วนลูปส่งข้อความไปยัง sale_line_user_id แต่ละรายการ
    $line_no = 0;
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) > 0) {
        foreach ($results as $row) {
            $line_no++;
            $line_user_id = $row['data'];
            echo $line_no . " | " . $row['sale_name_desc'] . " | " . $line_user_id . " | " . $msg;
            send_Message($access_token, $line_user_id, $msg);
        }
    } else {
        echo "ไม่พบข้อมูลผู้ใช้";
    }
}

