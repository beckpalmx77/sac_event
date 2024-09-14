<?php
include('includes/Header.php');
?>


    <!DOCTYPE html>
    <html lang="th">
    <body id="page-top">

    <style>
        .large-text {
            font-size: 100px; /* ปรับขนาดตัวอักษรตามที่คุณต้องการ */
        }

        .medium-text {
            font-size: 40px; /* ปรับขนาดตัวอักษรตามที่คุณต้องการ */
        }

        .icon-input-btn {
            display: inline-block;
            position: relative;
        }

        .icon-input-btn input[type="submit"] {
            padding-left: 2em;
        }

        .icon-input-btn .fa {
            display: inline-block;
            position: absolute;
            left: 0.65em;
            top: 30%;
        }
    </style>

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('includes/Top-Bar-Event.php'); ?>
                <div style="text-align: center;">
                    <img src="img/header_a.png" id="headerImage">
                </div>
                <div class="container-fluid" id="container-wrapper">
                    <div class="row mb-3">
                        <!-- Card 1 -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col mr-2">
                                            <div class="medium-text font-weight-bold text-uppercase mb-1">
                                                จำนวนผู้เข้าร่วมงานทั้งหมด
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <p class="text-primary large-text" id="Text1"></p>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-address-book fa-3x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="medium-text font-weight-bold text-uppercase mb-1">Check In</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <p class="text-success large-text" id="Text2"></p>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-address-book fa-3x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="medium-text font-weight-bold text-uppercase mb-1">Not Check In
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <p class="text-danger large-text" id="Text3"></p>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-address-book fa-3x text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="medium-text font-weight-bold text-uppercase mb-1">% Check In
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <p class="text-info large-text" id="Text-Percent"></p>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-address-book fa-3x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/myadmin.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="js/chart/chart-area-demo.js"></script>

    <link href='vendor/calendar/main.css' rel='stylesheet'/>
    <script src='vendor/calendar/main.js'></script>
    <script src='vendor/calendar/locales/th.js'></script>

    <script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="vendor/datatables/v11/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="vendor/datatables/v11/buttons.dataTables.min.css"/>

    <script>
        $(document).ready(function () {
            // ปรับขนาดหน้าจอ
            adjustScreenHeight();

            $(window).resize(function () {
                adjustScreenHeight();
            });

            function adjustScreenHeight() {
                let contentWrapper = $('#content-wrapper');
                let windowHeight = $(window).height();
                let contentHeight = contentWrapper.outerHeight();

                if (contentHeight > windowHeight) {
                    contentWrapper.css('height', 'auto');
                } else {
                    contentWrapper.css('height', windowHeight + 'px');
                }

                // ซ่อนหรือแสดงรูปภาพตามความสูงของหน้าจอ
                if (windowHeight < 900) {
                    $('#headerImage').hide();
                } else {
                    $('#headerImage').show();
                }
            }

            // ปรับขนาด Font ของ Icon Input Button
            $(".icon-input-btn").each(function () {
                let btnFont = $(this).find(".btn").css("font-size");
                let btnColor = $(this).find(".btn").css("color");
                $(this).find(".fa").css({'font-size': btnFont, 'color': btnColor});
            });

            // ฟังก์ชัน GET_DATA
            function GET_DATA(table_name, idx) {
                let input_text = document.getElementById("Text" + idx);
                let action = "GET_SUM";
                let cond = "";
                let field = "";
                switch (idx) {
                    case 1:
                        action = "GET_SUM_ALL";
                        field = "register_qty";
                        break;
                    case 2:
                        field = "attendance_qty";
                        cond = " Where check_in_status = 'Y'";
                        break;
                }

                let formData = {action: action, table_name: table_name, cond: cond , field: field};
                $.ajax({
                    type: "POST",
                    url: 'model/manage_general_data.php',
                    data: formData,
                    success: function (response) {
                        input_text.innerHTML = response;
                    },
                    error: function (response) {
                        alertify.error("error : " + response);
                    }
                });

                let value1 = document.getElementById("Text1").innerText;
                let value2 = document.getElementById("Text2").innerText;
                let value3 = document.getElementById("Text1").innerText - document.getElementById("Text2").innerText;
                let number1 = parseFloat(value1);
                let number2 = parseFloat(value2);
                let result = (number2 * 100) / number1;
                result = isNaN(result) ? 0 : result.toFixed(2);
                document.getElementById("Text3").innerText = value3;
                document.getElementById("Text-Percent").innerText = result;
            }

            // เรียกใช้งาน GET_DATA ทุก 3 วินาที
            GET_DATA("evs_event_checkin", 1);
            GET_DATA("evs_event_checkin", 2);

            setInterval(function () {
                GET_DATA("evs_event_checkin", 1);
                GET_DATA("evs_event_checkin", 2);
            }, 3000);

            // รีโหลด DataTable ทุก 7 วินาที
            let dataTable = $('#evsDataTable').DataTable({
                responsive: true,
                searching: false,
                paging: true,
                info: false,
                ordering: false,
                ajax: {
                    url: "model/manage_general_data.php",
                    type: "POST",
                    data: {action: "GET_TABLE_RECORDS"},
                },
                columns: [
                    {data: "checkin_status"},
                    {data: "name"},
                    {data: "id_card"},
                    {data: "reg_date"},
                    {data: "reg_time"}
                ]
            });

            setInterval(function () {
                dataTable.ajax.reload();
            }, 7000);

            // ปุ่ม Random
            $("#Random").click(function () {
                let input_text = document.getElementById("Text1");
                let val = Math.floor((Math.random() * input_text.innerHTML) + 1);
                //alert(val);
            });
        });
    </script>

    </body>
    </html>

