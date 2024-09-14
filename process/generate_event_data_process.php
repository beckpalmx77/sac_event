<?php
include('../config/connect_db.php');

$sql_event_find = "SELECT * FROM evs_event_master WHERE status = 'Y' ORDER BY id ";

$statement = $conn->query($sql_event_find);
$evs_results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($evs_results as $evs_result) {

    $sql_customer_find = "SELECT * FROM evs_customer ORDER BY cust_id ";

/*
    $myfile = fopen("qry_file1.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_customer_find);
    fclose($myfile);
*/

    $statement = $conn->query($sql_customer_find);
    $cust_results = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cust_results as $cust_result) {

        $sql_find = "SELECT * FROM evs_event_checkin WHERE event_id = '" . $evs_result['event_id'] . "' AND cust_id = '" . $cust_result['cust_id'] . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows <= 0) {
            $sql = "INSERT INTO evs_event_checkin(event_id,cust_id,cust_name_1,cust_name_2,cust_name_3,cust_name_4,cust_name_5,cust_name_6) 
                           VALUES (:event_id,:cust_id,:cust_name_1,:cust_name_2,:cust_name_3,:cust_name_4,:cust_name_5,:cust_name_6)";
            $query = $conn->prepare($sql);
            $query->bindParam(':event_id', $evs_result['event_id'], PDO::PARAM_STR);
            $query->bindParam(':cust_id', $cust_result['cust_id'], PDO::PARAM_STR);
            $query->bindParam(':cust_name_1', $cust_result['cust_name_1'], PDO::PARAM_STR);
            $query->bindParam(':cust_name_2', $cust_result['cust_name_2'], PDO::PARAM_STR);
            $query->bindParam(':cust_name_3', $cust_result['cust_name_3'], PDO::PARAM_STR);
            $query->bindParam(':cust_name_4', $cust_result['cust_name_4'], PDO::PARAM_STR);
            $query->bindParam(':cust_name_5', $cust_result['cust_name_5'], PDO::PARAM_STR);
            $query->bindParam(':cust_name_6', $cust_result['cust_name_6'], PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $conn->lastInsertId();
            if ($lastInsertId) {
                echo $cust_result['cust_id'] . " Save OK" . "\n\r";
            } else {
                echo "Error";
            }
        }

    }








}

