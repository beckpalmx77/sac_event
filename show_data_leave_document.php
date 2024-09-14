<?php

include("config/connect_db.php");

$month = $_POST["month"];
$year = $_POST["year"];
$branch = $_POST["branch"];

/*
$myfile = fopen("leave-param.txt", "w") or die("Unable to open file!");
fwrite($myfile, $month . "|" . $year . "|" . $branch);
fclose($myfile);
*/

$month_name = "";

$sql_month = " SELECT * FROM ims_month where month = '" . $month . "'";
$stmt_month = $conn->prepare($sql_month);
$stmt_month->execute();
$MonthRecords = $stmt_month->fetchAll();
foreach ($MonthRecords as $row) {
    $month_id = $row["month_id"];
    $month_name = $row["month_name"];
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
    <i class="fa fa-signal" aria-hidden="true"></i> แสดงข้อมูลการลา-เปลี่ยนวันหยุด-วันหยุดนักขัตฤกษ์ พนักงาน
    <?php echo " เดือน " . $month_name . " ปี " . $year; ?>
</div>

<div class="card-body">
    <a id="myLink" href="#" onclick="PrintPage();"><i class="fa fa-print"></i> พิมพ์</a>
</div>

<div class="card-body">

    <div class="card-body">
        <h4><span class="badge bg-success">แสดงข้อมูลการลา พนักงาน</span></h4>
        <table id="example" class="display table table-striped table-bordered"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>วันที่เอกสาร</th>
                <th>ชื่อพนักงาน</th>
                <th>หน่วยงาน</th>
                <th>ประเภทการลา</th>
                <th>วันที่ลาเริ่มต้น</th>
                <th>วันที่ลาสิ้นสุด</th>
                <th>หมายเหตุ</th>
            </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            <?php

            $date = date("d/m/Y");
            $total = 0;
            $total_payment = 0;
            $sql_leave = " SELECT * FROM v_dleave_event 
                WHERE doc_year = '" . $year . "' 
                AND doc_month = '" . $month_id . "'
                AND dept_id = '" . $branch . "'
                ORDER BY doc_date ";

            $statement_leave = $conn->query($sql_leave);
            $results_leave = $statement_leave->fetchAll(PDO::FETCH_ASSOC);
            $line_no = 0;
            foreach ($results_leave as $row_leave) {
            $line_no++;
            ?>

            <tr>
                <td><?php echo htmlentities($line_no); ?></td>
                <td><?php echo htmlentities($row_leave['doc_date']); ?></td>
                <td><?php echo htmlentities($row_leave['f_name'] . " " . $row_leave['l_name']); ?></td>
                <td><?php echo htmlentities($row_leave['department_id']); ?></td>
                <td><?php echo htmlentities($row_leave['leave_type_detail']); ?></td>
                <td><?php echo htmlentities($row_leave['date_leave_start']); ?></td>
                <td><?php echo htmlentities($row_leave['date_leave_to']); ?></td>
                <td><?php echo htmlentities($row_leave['remark']); ?></td>
                </td>
            </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<div class="card-body">

    <div class="card-body">
        <h4><span class="badge bg-info">แสดงข้อมูลการใช้วันหยุด (นักขัตฤกษ์-ประจำปี) พนักงาน</span></h4>
        <table id="example" class="display table table-striped table-bordered"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>วันที่เอกสาร</th>
                <th>ชื่อพนักงาน</th>
                <th>หน่วยงาน</th>
                <th>ประเภท</th>
                <th>วันที่เริ่มต้น</th>
                <th>วันที่สิ้นสุด</th>
                <th>หมายเหตุ</th>
            </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            <?php

            $date = date("d/m/Y");
            $total = 0;
            $total_payment = 0;
            $sql_leave = " SELECT * FROM vdholiday_event 
                WHERE doc_year = '" . $year . "' 
                AND month = '" . $month_id . "'
                AND dept_id = '" . $branch . "'
                ORDER BY doc_date ";

            $statement_leave = $conn->query($sql_leave);
            $results_leave = $statement_leave->fetchAll(PDO::FETCH_ASSOC);
            $line_no = 0;
            foreach ($results_leave as $row_leave) {
                $line_no++;
                ?>

                <tr>
                    <td><?php echo htmlentities($line_no); ?></td>
                    <td><?php echo htmlentities($row_leave['doc_date']); ?></td>
                    <td><?php echo htmlentities($row_leave['f_name'] . " " . $row_leave['l_name']); ?></td>
                    <td><?php echo htmlentities($row_leave['department_id']); ?></td>
                    <td><?php echo htmlentities($row_leave['leave_type_detail']); ?></td>
                    <td><?php echo htmlentities($row_leave['date_leave_start']); ?></td>
                    <td><?php echo htmlentities($row_leave['date_leave_to']); ?></td>
                    <td><?php echo htmlentities($row_leave['remark']); ?></td>
                    </td>
                </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
</div>


<div class="card-body">

    <div class="card-body">
        <h4><span class="badge bg-warning">แสดงข้อมูลการเปลี่ยนวันหยุด พนักงาน</span></h4>
        <table id="example" class="display table table-striped table-bordered"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>วันที่เอกสาร</th>
                <th>ชื่อพนักงาน</th>
                <th>หน่วยงาน</th>
                <th>ประเภท</th>
                <th>วันที่หยุดปกติ</th>
                <th>วันที่ต้องการหยุด</th>
                <th>หมายเหตุ</th>
            </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            <?php

            $date = date("d/m/Y");
            $total = 0;
            $total_payment = 0;
            $sql_leave = " SELECT * FROM v_dchange_event 
                WHERE doc_year = '" . $year . "' 
                AND doc_month = '" . $month_id . "'
                AND dept_id = '" . $branch . "'
                ORDER BY doc_date ";

            $statement_leave = $conn->query($sql_leave);
            $results_leave = $statement_leave->fetchAll(PDO::FETCH_ASSOC);
            $line_no = 0;
            foreach ($results_leave as $row_leave) {
                $line_no++;
                ?>

                <tr>
                    <td><?php echo htmlentities($line_no); ?></td>
                    <td><?php echo htmlentities($row_leave['doc_date']); ?></td>
                    <td><?php echo htmlentities($row_leave['f_name'] . " " . $row_leave['l_name']); ?></td>
                    <td><?php echo htmlentities($row_leave['department_id']); ?></td>
                    <td><?php echo htmlentities($row_leave['leave_type_detail']); ?></td>
                    <td><?php echo htmlentities($row_leave['date_leave_start']); ?></td>
                    <td><?php echo htmlentities($row_leave['date_leave_to']); ?></td>
                    <td><?php echo htmlentities($row_leave['remark']); ?></td>
                    </td>
                </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
</div>


</body>
</html>

