<?php
include('../config/connect_db.php');

$year = date("Y");
$month = date("m");
$date = date("d");

$current_date = $date . "-" . $month . "-" . $year;
$day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$sql_find = "SELECT * FROM job_payment_month_total WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "'";


$nRows = $conn->query($sql_find)->fetchColumn();
if ($nRows <= 0) {
    $effect_start_date = "01-" . $month . "-" . $year;
    $effect_to_date = $day . "-" . $month . "-" . $year;
    echo $effect_start_date . " | " . $effect_to_date;
    $sql = "INSERT INTO job_payment_month_total(effect_start_date,effect_to_date,effect_month,effect_year) 
                   VALUES (:effect_start_date,:effect_to_date,:effect_month,:effect_year)";
    $query = $conn->prepare($sql);
    $query->bindParam(':effect_start_date', $effect_start_date, PDO::PARAM_STR);
    $query->bindParam(':effect_to_date', $effect_to_date, PDO::PARAM_STR);
    $query->bindParam(':effect_month', $month, PDO::PARAM_STR);
    $query->bindParam(':effect_year', $year, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $conn->lastInsertId();
    if ($lastInsertId) {
        echo "OK";
    } else {
        echo "Error";
    }
}

for ($date_loop = 1; $date_loop <= $date; $date_loop++) {

    $date_ins = sprintf('%02s', $date_loop) . "-" . $month . "-" . $year;

    $sql_find = "SELECT * FROM job_payment_daily_total WHERE job_date = '" . $date_ins . "'";

    $nRows = $conn->query($sql_find)->fetchColumn();

    if ($nRows <= 0) {
        $date_ins_calendar = $year . "-" . $month . "-" . sprintf('%02s', $date_loop);

        $sql = "INSERT INTO job_payment_daily_total(job_date,job_date_calendar,effect_month,effect_year) 
                       VALUES (:job_date,:job_date_calendar,:effect_month,:effect_year)";
        $query = $conn->prepare($sql);
        $query->bindParam(':job_date', $date_ins, PDO::PARAM_STR);
        $query->bindParam(':job_date_calendar', $date_ins_calendar, PDO::PARAM_STR);
        $query->bindParam(':effect_month', $month, PDO::PARAM_STR);
        $query->bindParam(':effect_year', $year, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $conn->lastInsertId();
        if ($lastInsertId) {
            echo $date_loop . " | " . $month . " | " . $year . " save OK" . "\n\r";
        } else {
            echo "Error";
        }
    }

    $sql_emp_find = "SELECT * FROM job_memployee WHERE emp_status = 'Y' ORDER BY id ";
    $statement = $conn->query($sql_emp_find);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $date_ins = sprintf('%02s', $date_loop) . "-" . $month . "-" . $year;
        $sql_find = "SELECT * FROM job_transaction WHERE job_date = '" . $date_ins . "' AND emp_id = '" . $result['emp_id'] . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows <= 0) {
            $date_ins_calendar = $year . "-" . $month . "-" . sprintf('%02s', $date_loop);
            $sql = "INSERT INTO job_transaction(job_date,effect_month,effect_year,job_date_calendar,emp_id) 
                           VALUES (:job_date,:effect_month,:effect_year,:job_date_calendar,:emp_id)";
            $query = $conn->prepare($sql);
            $query->bindParam(':job_date', $date_ins, PDO::PARAM_STR);
            $query->bindParam(':effect_month', $month, PDO::PARAM_STR);
            $query->bindParam(':effect_year', $year, PDO::PARAM_STR);
            $query->bindParam(':job_date_calendar', $date_ins_calendar, PDO::PARAM_STR);
            $query->bindParam(':emp_id', $result['emp_id'], PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $conn->lastInsertId();
            if ($lastInsertId) {
                echo $date_ins . " | " . $result['emp_id'] . " Save OK" . "\n\r";
            } else {
                echo "Error";
            }
        }
    }
}