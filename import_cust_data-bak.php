<?php
include 'config/connect_db.php';
require 'vendor/autoload.php'; // Load PhpSpreadsheet
include 'util/check_phone_format.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Fetch the latest event
$sql_event_find = "SELECT * FROM evs_event_master WHERE status = 'Y' ORDER BY id DESC LIMIT 1";
$statement = $conn->query($sql_event_find);
$evs_result = $statement->fetch(PDO::FETCH_ASSOC);
if ($evs_result) {
    $event_id = $evs_result['event_id'];
    $event_desc = $evs_result['event_desc'];
} else {
    die("No active event found.");
}

if (isset($_FILES['excelFile']['name']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['excelFile']['tmp_name'];

    $spreadsheet = IOFactory::load($fileTmp);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    $importedRows = 0;
    $duplicateRows = 0;

    foreach ($rows as $index => $row) {
        if ($index == 0) continue; // Skip header row

        $cust_id = ($row[0] === "" || $row[0] === null) ? "-" : $row[0];
        $ar_name = ($row[1] === "" || $row[1] === null) ? "-" : $row[1];
        $register_qty = ($row[2] === "" || $row[2] === null) ? "0" : $row[2];
        $cust_name_1 = ($row[3] === "" || $row[3] === null) ? "-" : $row[3];
        $cust_name_2 = ($row[4] === "" || $row[4] === null) ? "-" : $row[4];
        $cust_name_3 = ($row[5] === "" || $row[5] === null) ? "-" : $row[5];
        $cust_name_4 = ($row[6] === "" || $row[6] === null) ? "-" : $row[6];
        $cust_name_5 = ($row[7] === "" || $row[7] === null) ? "-" : $row[7];
        $cust_name_6 = ($row[8] === "" || $row[8] === null) ? "-" : $row[8];
        $phone = checkAndFormatPhoneNumber($row[9]);
        $phone = ($phone === "" || $phone === null) ? "-" : $phone;
        $province_name = ($row[10] === "" || $row[10] === null) ? "-" : $row[10];
        $sale_contact_name = ($row[11] === "" || $row[11] === null) ? "-" : $row[11];

        // Check for duplicates in evs_customer
        $statement = $conn->prepare("SELECT COUNT(*) FROM evs_customer WHERE cust_id = ?");
        $statement->execute([$cust_id]);
        if ($statement->fetchColumn() == 0 && $cust_id !== '-' && $register_qty !== '0') {
            // Insert new customer
            $stmt_insert_customer = $conn->prepare(
                "INSERT INTO evs_customer (cust_id, ar_name, cust_name_1, cust_name_2, cust_name_3, cust_name_4, cust_name_5, cust_name_6, phone, province_name, sale_contact_name)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt_insert_customer->execute([$cust_id, $ar_name, $cust_name_1, $cust_name_2, $cust_name_3, $cust_name_4, $cust_name_5, $cust_name_6, $phone, $province_name, $sale_contact_name]);

            // Check for duplicates in evs_event_checkin
            $sql_find_checkin = "SELECT COUNT(*) FROM evs_event_checkin WHERE event_id = ? AND cust_id = ?";
            $stmt_find_checkin = $conn->prepare($sql_find_checkin);
            $stmt_find_checkin->execute([$event_id, $cust_id]);
            if ($stmt_find_checkin->fetchColumn() == 0) {
                // Insert new check-in record
                $stmt_insert_checkin = $conn->prepare(
                    "INSERT INTO evs_event_checkin (event_id, cust_id, cust_name_1, cust_name_2, cust_name_3, cust_name_4, cust_name_5, cust_name_6, register_qty)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt_insert_checkin->execute([$event_id, $cust_id, $cust_name_1, $cust_name_2, $cust_name_3, $cust_name_4, $cust_name_5, $cust_name_6, $register_qty]);
                $importedRows++;
            } else {
                $duplicateRows++;
            }
        } else {
            $duplicateRows++;
        }
    }

    echo "Imported: $importedRows, Duplicates: $duplicateRows";
} else {
    echo "No file uploaded or error in file upload.";
}

