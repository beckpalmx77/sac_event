<?php
include 'config/connect_db.php';
require 'vendor/autoload.php'; // โหลด PhpSpreadsheet
include 'util/check_phone_format.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$sql_event_find = "SELECT * FROM evs_event_master WHERE status = 'Y' ORDER BY id DESC LIMIT 1  ";
$statement = $conn->query($sql_event_find);
$evs_results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($evs_results as $evs_result) {
    $event_id = $evs_result['event_id'];
    $event_desc = $evs_result['event_desc'];
}

if (isset($_FILES['excelFile']['name'])) {
    $fileName = $_FILES['excelFile']['name'];
    $fileTmp = $_FILES['excelFile']['tmp_name'];

    $spreadsheet = IOFactory::load($fileTmp);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    $importedRows = 0;
    $duplicateRows = 0;

    foreach ($rows as $index => $row) {
        if ($index == 0) continue; // Skip header row

        if ($row[2] !== "" && $row[2] !== "0" && $row[2] !== null) {

            $cust_id = ($row[0] === "" || $row[0] === null) ? "-" : $row[0];
            $ar_name = ($row[1] === "" || $row[1] === null) ? "-" : $row[1];
            $attendance_qty = ($row[2] === "" || $row[2] === null) ? "0" : $row[2];
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

            // Check for duplicates
            $statement = $conn->prepare("SELECT COUNT(*) FROM evs_customer WHERE cust_id = ?");
            $statement->execute([$cust_id]);
            if ($statement->fetchColumn() == 0) {

                // Insert new record
                $statement = $conn->prepare("INSERT INTO evs_customer (cust_id,ar_name,cust_name_1,cust_name_2,cust_name_3,cust_name_4,cust_name_5,cust_name_6,phone,province_name,sale_contact_name) VALUES (?,?,?,?, ?,?, ?,?,?,?,?)");
                if ($statement->execute([$cust_id, $ar_name, $cust_name_1, $cust_name_2, $cust_name_3, $cust_name_4, $cust_name_5, $cust_name_6, $phone, $province_name, $sale_contact_name])) {

                    $sql_find = "SELECT * FROM evs_event_checkin WHERE event_id = '" . $event_id . "' AND cust_id = '" . $cust_id . "'";
                    $nRows = $conn->query($sql_find)->fetchColumn();
                    if ($nRows <= 0) {
                        $sql = "INSERT INTO evs_event_checkin(event_id,cust_id,cust_name_1,cust_name_2,cust_name_3,cust_name_4,cust_name_5,cust_name_6,attendance_qty) 
                           VALUES (:event_id,:cust_id,:cust_name_1,:cust_name_2,:cust_name_3,:cust_name_4,:cust_name_5,:cust_name_6,:attendance_qty)";
                        $query = $conn->prepare($sql);
                        $query->bindParam(':event_id', $event_id, PDO::PARAM_STR);
                        $query->bindParam(':cust_id', $cust_id, PDO::PARAM_STR);
                        $query->bindParam(':cust_name_1', $cust_name_1, PDO::PARAM_STR);
                        $query->bindParam(':cust_name_2', $cust_name_2, PDO::PARAM_STR);
                        $query->bindParam(':cust_name_3', $cust_name_3, PDO::PARAM_STR);
                        $query->bindParam(':cust_name_4', $cust_name_4, PDO::PARAM_STR);
                        $query->bindParam(':cust_name_5', $cust_name_5, PDO::PARAM_STR);
                        $query->bindParam(':cust_name_6', $cust_name_6, PDO::PARAM_STR);
                        $query->bindParam(':cust_name_6', $cust_name_6, PDO::PARAM_STR);
                        $query->bindParam(':attendance_qty', $attendance_qty, PDO::PARAM_STR);
                        $query->execute();
                        $lastInsertId = $conn->lastInsertId();
                        if ($lastInsertId) {
                            echo $cust_id . " Save OK" . "\n\r";
                        } else {
                            echo "Error";
                        }
                    }

                    $importedRows++;
                }

            } else {
                $duplicateRows++;
            }

        }
    }

    echo "Imported: $importedRows, Duplicates: $duplicateRows";
}

