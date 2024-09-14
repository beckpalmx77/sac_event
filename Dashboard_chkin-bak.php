<?php
include('includes/Header.php');
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>

    <!-- Stylesheets -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/myadmin.css" rel="stylesheet">
    <link href="vendor/datatables/v11/jquery.dataTables.min.css" rel="stylesheet">
    <link href="vendor/datatables/v11/buttons.dataTables.min.css" rel="stylesheet">
    <link href="vendor/calendar/main.css" rel="stylesheet">

    <!-- Custom styles -->
    <style>
        .large-text {
            font-size: 50px;
        }

        .medium-text {
            font-size: 20px;
        }

        .icon-input-btn {
            display: inline-block;
            position: relative;
        }

        .icon-input-btn input[type="submit"] {
            padding-left: 2em;
        }

        .icon-input-btn .fa {
            position: absolute;
            left: 0.65em;
            top: 30%;
        }

        #attendee-list table {
            color: black;
        }

        #attendee-list tbody tr:nth-child(odd) {
            background-color: white;
        }

        #attendee-list tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body id="page-top">
<div id="wrapper">
    <!--?php include('includes/Side-Bar.php'); ?-->

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('includes/Top-Bar-Event.php'); ?>

            <div class="container-fluid" id="container-wrapper">
                <div id="attendee-list">
                    <table id="TableRecordList" class="table table-bordered table-striped" style="border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th style="text-align: left;"><h3 style="color: #0000FF;">ลำดับที่</h3></th>
                            <th style="text-align: left;"><h3 style="color: #0000FF;">ผู้เข้าร่วมงาน</h3></th>
                            <th style="text-align: left;"><h3 style="color: #0000FF;">หมายเลขโต๊ะ</h3></th>
                            <th style="text-align: left;"><h3 style="color: #0000FF;">จังหวัด</h3></th>
                            <th style="text-align: left;"><h3 style="color: #0000FF;">สถานะเช็คอิน</h3></th>
                            <th style="text-align: left;"><h3 style="color: #0000FF;">เวลาเช็คอิน</h3></th>
                        </tr>
                        </thead>
                        <tbody id="attendee-tbody">
                        <!-- รายการจะถูกแสดงที่นี่ -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/Modal-Logout.php');
?>

<!-- Scroll to top -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/myadmin.min.js"></script>
<script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/v11/dataTables.buttons.min.js"></script>
<script src="vendor/datatables/v11/jszip.min.js"></script>
<script src="vendor/datatables/v11/pdfmake.min.js"></script>
<script src="vendor/datatables/v11/vfs_fonts.js"></script>
<script src="vendor/datatables/v11/buttons.html5.min.js"></script>
<script src="vendor/datatables/v11/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {

        function fetchAttendees() {
            $.ajax({
                url: 'fetch_attendees.php',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    displayAttendees(data);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching attendees:', error);
                }
            });
        }

        function displayAttendees(attendees) {
            const $tbody = $('#attendee-tbody');
            $tbody.empty();

            attendees.forEach(function (attendee) {
                $tbody.append(
                    `<tr>
                        <td><H3>${attendee.order_record}</H3></td>
                        <td><H3>${attendee.ar_name}</H3></td>
                        <td><H3>${attendee.table_number}</H3></td>
                        <td><H3>${attendee.province_name}</H3></td>
                        <td><H3>${attendee.check_in_status_text}</H3></td>
                        <td><H3>${attendee.update_chk_in_date}</H3></td>
                    </tr>`
                );
            });

            // Initialize DataTable after fetching data
            $('#TableRecordList').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 10, // กำหนดจำนวน record ต่อหน้า
                "lengthChange": false, // ปิดการเปลี่ยนจำนวนต่อหน้า
                "destroy": true // ทำลาย table เดิมเมื่อมีการสร้างใหม่
            });
        }

        // Fetch attendee list every 4 seconds
        setInterval(fetchAttendees, 4000);

        // Initial fetch
        fetchAttendees();
    });
</script>
</body>
</html>
