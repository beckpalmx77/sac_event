<?php

include("config/connect_db.php");

$month = $_POST["month"];
$year = $_POST["year"];
$month_name = "";

$sql_month = " SELECT * FROM ims_month where month = '" . $month . "'";
$stmt_month = $conn->prepare($sql_month);
$stmt_month->execute();
$MonthRecords = $stmt_month->fetchAll();
foreach ($MonthRecords as $row) {
    $month_id = $row["month_id"];
    $month_name = $row["month_name"];
}

$sql_total = " SELECT * FROM job_payment_month_total 
                WHERE effect_year = '" . $year . "' 
                AND effect_month = '" . $month_id . "'";

$statement_total = $conn->query($sql_total);
$results_total = $statement_total->fetchAll(PDO::FETCH_ASSOC);
foreach ($results_total as $row_total) {
    $date_start_to = $row_total['effect_start_date'] . " - " .  $row_total['effect_to_date'];
    $total_tires = $row_total['total_tires'];
    $total_money = $row_total['total_money'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta date="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <script src="js/jquery-3.6.0.js"></script>
    <!--script src="js/chartjs-2.9.0.js"></script-->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="fontawesome/css/font-awesome.css">

    <link href='vendor/calendar/main.css' rel='stylesheet'/>
    <script src='vendor/calendar/main.js'></script>
    <script src='vendor/calendar/locales/th.js'></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script src='js/util.js'></script>

    <title>สงวนออโต้คาร์</title>

    <style>
        table {
            width: 50%;
        }
    </style>

</head>

<p class="card">
<div class="card-header bg-primary text-white">
    <i class="fa fa-signal" aria-hidden="true"></i> สรุปข้อมูลการจ่ายค่าแรงพันยาง
    <?php echo " เดือน " . $month_name . " ปี " . $year; ?>
</div>

<div class="card-body">
    <a id="myLink" href="#" onclick="PrintPage();"><i class="fa fa-print"></i> พิมพ์</a>
</div>

<div class="card-body">

    <div class="card-body">
        <h4><span class="badge bg-success">สรุปข้อมูลการจ่ายค่าแรงพันยาง (เดือน) <?php echo "วันที่ " . $date_start_to ?></span></h4>
        <h4><span class="badge bg-success">จำนวนยาง = <?php echo $total_tires  ." เส้น จำนวนเงินที่จ่าย  = "  . $total_money . " บาท " ?></span></h4>
        <table id="example" class="display table table-striped table-bordered"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>ชื่อพนักงาน</th>
                <th>เดือน</th>
                <th>ปี</th>
                <th>ยอดเงินที่ได้</th>
            </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            <?php

            $date = date("d/m/Y");
            $total = 0;
            $total_payment = 0;
            $sql_total = " SELECT * FROM v_job_transaction__summary 
                WHERE effect_year = '" . $year . "' 
                AND effect_month = '" . $month_id . "'
                ORDER BY emp_id ";

            $myfile = fopen("job-getdata.txt", "w") or die("Unable to open file!");
            fwrite($myfile, $sql_total);
            fclose($myfile);

            $statement_total = $conn->query($sql_total);
            $results_total = $statement_total->fetchAll(PDO::FETCH_ASSOC);
            $line_no = 0;
            foreach ($results_total as $row_total) {
            $line_no++;
                ?>

            <tr>
                <td><?php echo htmlentities($line_no); ?></td>
                <td><?php echo htmlentities($row_total['f_name']); ?></td>
                <td><?php echo htmlentities($month_name); ?></td>
                <td><?php echo htmlentities($year); ?></td>
                <td align="right"><p class="number"><?php echo htmlentities(number_format($row_total['total_money'], 2)); ?></p>
                </td>
                <?php
                $total_payment = $total_payment + $row_total['total_money'];
            } ?>
            </tr>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>

