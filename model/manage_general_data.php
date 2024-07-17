<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');

$table_name = $_POST["table_name"];

if ($_POST["action"] === 'GET_COUNT_RECORDS') {
    $return_arr = array();
    $sql_get = "SELECT count(*) as record_counts  FROM " . $table_name;

/*
    $sql_sqlsvr1 .= $sql_get . "\n\r";
    $myfile = fopen($table_name . "-1.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_sqlsvr1);
    fclose($myfile);
*/

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $record = $result['record_counts'];
    }
    echo $record;
}

if ($_POST["action"] === 'GET_COUNT_RECORDS_COND') {
    $cond = $_POST["cond"];
    $return_arr = array();
    $sql_get = "SELECT count(*) as record_counts  FROM " . $table_name . " " . $cond;


    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $record = $result['record_counts'];
    }
    echo $record;
}