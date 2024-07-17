<?php
include('../config/connect_db.php');

$temp_c = $_GET["temp_c"];
$temp_f = $_GET["temp_f"];
$humidity = $_GET["humidity"];
$heat_index = $_GET["heat_index"];
$ip_address = $_GET["ip_address"];
$ssid = $_GET["ssid"];


$currentDate = date('Y-m-d H:i:s');

$status = 'N';


$sql = "INSERT INTO temperature_data_iot(date_time,temperature_c,temperature_f,humidity,heat_index,ip_address,ssid,status)
            VALUES (:date_time,:temperature_c,:temperature_f,:humidity,:heat_index,:ip_address,:ssid,:status)";

/*
$sql = "INSERT INTO temperature_data_iot(date_time,temperature_c,temperature_f,humidity,heat_index,status) 
            VALUES (:date_time,:temperature_c,:temperature_f,:humidity,:heat_index,:status)";
*/

$query = $conn->prepare($sql);
$query->bindParam(':date_time', $currentDate, PDO::PARAM_STR);
$query->bindParam(':temperature_c', $temp_c, PDO::PARAM_STR);
$query->bindParam(':temperature_f', $temp_f, PDO::PARAM_STR);
$query->bindParam(':humidity', $humidity, PDO::PARAM_STR);
$query->bindParam(':heat_index', $heat_index, PDO::PARAM_STR);
$query->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
$query->bindParam(':ssid', $ssid, PDO::PARAM_STR);
$query->bindParam(':status', $status, PDO::PARAM_STR);
$query->execute();
$lastInsertId = $conn->lastInsertId();

if ($lastInsertId) {

    //$res = notify_message($str, $token);

    echo 1;
} else {
    echo 0;
}