<?php

include('../config/connect_db.php');


$provinces = array();

$sql_get = "SELECT * FROM provinces";

$statement = $conn->query($sql_get);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {
    $provinces[] = array("id" => $result['id'],
        "province_code" => $result['province_code'],
        "province_name" => $result['province_name']);
}
/*
$txt = $sql_get . " | " . $provinces ;
$my_file = fopen("province_qa.txt", "w") or die("Unable to open file!");
fwrite($my_file, $txt);
fclose($my_file);

$file = "province.json";

file_put_contents($file, json_encode($provinces));
*/

echo json_encode($provinces);
