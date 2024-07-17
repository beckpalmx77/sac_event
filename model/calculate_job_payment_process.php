<?php
/*
include('../config/connect_db.php');

$year = date("Y");
$month = date("m");
$date = date("d");
*/

$month = $_POST["effect_month"];
$year = $_POST["effect_year"];
$job_date_transaction = $_POST["job_date_trans"];
$total_job_emp = 0;

$sql_find = "SELECT job_date , COUNT(*) AS Record FROM job_transaction
WHERE grade_point in ('A','B','C') AND effect_month = '" . $month . "' AND effect_year = '" . $year . "' GROUP BY job_date ";
$statement = $conn->query($sql_find);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($results as $result) {
//echo $sql_find;
    $sql = "UPDATE job_payment_daily_total SET total_job_emp =:total_job_emp WHERE job_date = :job_date";
    $query = $conn->prepare($sql);
    $query->bindParam(':total_job_emp', $result['Record'], PDO::PARAM_STR);
    $query->bindParam(':job_date', $result['job_date'], PDO::PARAM_STR);
    $query->execute();

}

$sql_find2 = "SELECT job_date , sum(percent) AS percent FROM v_job_transaction WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "' GROUP BY job_date ";
//echo "Update2 = " . $sql_find2;
$statement = $conn->query($sql_find2);
$results2 = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results2 as $result2) {
//echo $sql_find2;
    $sql2 = "UPDATE job_payment_daily_total SET total_grade_point =:total_grade_point WHERE job_date = :job_date";
    $query = $conn->prepare($sql2);
    $query->bindParam(':total_grade_point', $result2['percent'], PDO::PARAM_STR);
    $query->bindParam(':job_date', $result2['job_date'], PDO::PARAM_STR);
    $query->execute();
}

$sql_find3 = "select effect_month,effect_year,sum(total_tires) as total_tires from job_payment_daily_total
WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "'
GROUP BY effect_month , effect_year";

$statement = $conn->query($sql_find3);
$results3 = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results3 as $result3) {

    $sql3 = "UPDATE job_payment_month_total SET total_tires =:total_tires WHERE effect_month = :effect_month AND effect_year = :effect_year ";
    $query = $conn->prepare($sql3);
    $query->bindParam(':total_tires', $result3['total_tires'], PDO::PARAM_STR);
    $query->bindParam(':effect_month', $result3['effect_month'], PDO::PARAM_STR);
    $query->bindParam(':effect_year', $result3['effect_year'], PDO::PARAM_STR);
    $query->execute();
}

$sql_find4 = "select job_date,emp_id,effect_month,effect_year,percent from v_job_transaction
WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "'";

$statement = $conn->query($sql_find4);
$results4 = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results4 as $result4) {
    $sql_find_daily = "select * from job_payment_daily_total
WHERE job_date = '" . $result4['job_date'] . "' AND effect_month = '" . $month . "' AND effect_year = '" . $year . "'";

    $statement = $conn->query($sql_find_daily);
    $results_daily = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results_daily as $result_daily) {

        if ($result4['percent'] !== null && $result4['percent'] !== 0 && $result4['percent'] !== '0' && $result4['percent'] !== '-'
            && $result_daily['total_grade_point'] !== null && $result_daily['total_grade_point'] !== 0 && $result_daily['total_grade_point'] !== '0' && $result_daily['total_grade_point'] !== '-'
            && $result_daily['total_grade_point'] > 0) {

            $total_percent_payment = ($result4['percent'] / $result_daily['total_grade_point']) * 100;
            $total_percent_payment = round($total_percent_payment, 2);

        } else {
            $total_percent_payment = '0';
        }

    }

    $sql4 = "UPDATE job_transaction SET total_grade_point =:total_grade_point , total_percent_payment =:total_percent_payment WHERE emp_id = :emp_id AND job_date = :job_date ";

    $query = $conn->prepare($sql4);
    $query->bindParam(':total_grade_point', $result4['percent'], PDO::PARAM_STR);
    $query->bindParam(':total_percent_payment', $total_percent_payment, PDO::PARAM_STR);
    $query->bindParam(':emp_id', $result4['emp_id'], PDO::PARAM_STR);
    $query->bindParam(':job_date', $result4['job_date'], PDO::PARAM_STR);
    $query->execute();

}

$sql_find_month = "SELECT * FROM job_payment_month_total WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "' ORDER BY id ";
//echo "Update2 = " . $sql_find2;
$statement = $conn->query($sql_find_month);
$results_month = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results_month as $result_month) {
    $effect_month = $result_month['effect_month'];
    $effect_year = $result_month['effect_year'];
    $total_tires = $result_month['total_tires'];
    $total_money = $result_month['total_money'];
}

$sql_find_daily = "SELECT job_date , sum(total_tires) AS total_tires , sum(total_grade_point) AS total_grade_point    
                   FROM job_payment_daily_total 
                   WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "' 
                   GROUP BY job_date 
                   ORDER BY job_date ";

$statement = $conn->query($sql_find_daily);
$results_daily = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results_daily as $result_daily) {

    if ($result_daily['total_tires'] !== null && $result_daily['total_tires'] !== 0 && $result_daily['total_tires'] !== '0' && $result_daily['total_tires'] !== '-'
        && $total_tires !== null && $total_tires !== 0 && $total_tires !== '0' && $total_tires !== '-') {

        $total_percent_payment = ($result_daily['total_tires'] / $total_tires) * 100;
        $total_percent_payment_round = round($total_percent_payment, 2);

    }

    if ($total_percent_payment !== null && $total_percent_payment !== 0 && $total_percent_payment !== '0' && $total_percent_payment !== '-'
        && $total_money !== null && $total_money !== 0 && $total_money !== '0' && $total_money !== '-' && $result_daily['total_grade_point'] > 0) {
        $total_pay_money = ($total_percent_payment / 100) * $total_money;
        $total_pay_money = round($total_pay_money, 2);
    } else {
        $total_percent_payment_round = '0';
        $total_pay_money = '0';
    }

    $sql_up_daily = "UPDATE job_payment_daily_total SET total_percent_payment =:total_percent_payment ,total_money=:total_money
WHERE job_date = :job_date ";
    $query = $conn->prepare($sql_up_daily);
    $query->bindParam(':total_percent_payment', $total_percent_payment_round, PDO::PARAM_STR);
    $query->bindParam(':total_money', $total_pay_money, PDO::PARAM_STR);
    $query->bindParam(':job_date', $result_daily['job_date'], PDO::PARAM_STR);
    $query->execute();

}


$sql_find_trans = "SELECT * FROM job_transaction WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "' ORDER BY job_date,emp_id ";

$statement = $conn->query($sql_find_trans);
$results_trans = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results_trans as $result_trans) {

    $sql_find_daily = "select * from job_payment_daily_total
WHERE job_date = '" . $result_trans['job_date'] . "' AND effect_month = '" . $month . "' AND effect_year = '" . $year . "'";

    $statement = $conn->query($sql_find_daily);
    $results_daily = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results_daily as $result_daily) {

        if (($result_daily['total_money'] !== null && $result_daily['total_money'] !== 0
                && $result_daily['total_money'] !== '0' && $result_daily['total_money'] !== '-'
                && $result_trans['total_percent_payment'] !== null && $result_trans['total_percent_payment'] !== 0
                && $result_trans['total_percent_payment'] !== '0' && $result_trans['total_percent_payment'] !== '-') && $result_trans['total_grade_point'] > 0) {

            $total_money_payment = ($result_daily['total_money'] * $result_trans['total_percent_payment'] / 100);
            $total_money_payment_round = number_format($total_money_payment, 2);
        } else {
            $total_money_payment = '0';
            $total_money_payment_round = '0';
        }

    }


    $sql_up_trans = "UPDATE job_transaction SET total_money=:total_money
WHERE emp_id =:emp_id AND job_date = :job_date ";
    $query = $conn->prepare($sql_up_trans);
    $query->bindParam(':total_money', $total_money_payment_round, PDO::PARAM_STR);
    $query->bindParam(':emp_id', $result_trans['emp_id'], PDO::PARAM_STR);
    $query->bindParam(':job_date', $result_trans['job_date'], PDO::PARAM_STR);
    $query->execute();

}