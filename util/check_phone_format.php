<?php

function checkAndFormatPhoneNumber($phoneNumber) {
    // ลบช่องว่างและเครื่องหมายที่ไม่ใช่ตัวเลขทั้งหมด
    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

    // ตรวจสอบความยาวของหมายเลขโทรศัพท์
    if (strlen($phoneNumber) == 9) {
        // ถ้าหมายเลขโทรศัพท์มีความยาว 9 หลัก ให้เติม 0 ด้านหน้า
        $phoneNumber = '0' . $phoneNumber;
    }

    // ตรวจสอบว่าเลข 0 อยู่ด้านหน้าหรือไม่
    if (strlen($phoneNumber) == 10 && $phoneNumber[0] != '0') {
        // ถ้าหมายเลขโทรศัพท์มีความยาว 10 หลักและไม่มีเลข 0 ด้านหน้า ให้เติม 0
        $phoneNumber = '0' . substr($phoneNumber, 1);
    }

    return $phoneNumber;
}
