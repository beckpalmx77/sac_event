<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

    ?>

    <!DOCTYPE html>
    <html lang="th">

    <style>

        .feedback {
            background-color: #31B0D5;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            border-color: #46b8da;
        }


        #menu_fix_button {
            position: fixed;
            bottom: 4px;
            right: 80px;
        }

    </style>

    <body id="page-top">
    <div id="wrapper">


        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><span id="title"></span></h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a>
                            </li>
                            <li class="breadcrumb-item"><span id="main_menu"></li>
                            <li class="breadcrumb-item active"
                                aria-current="page"><span id="sub_menu"></li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-12">
                                <!--div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                </div-->
                                <div class="card-body">
                                    <section class="container-fluid">
                                        <form method="post" id="MainrecordForm">
                                            <input type="hidden" class="form-control" id="KeyAddData" name="KeyAddData"
                                                   value="">
                                            <input type="hidden" class="form-control"
                                                   id="effect_month" name="effect_month">
                                            <input type="hidden" class="form-control"
                                                   id="month_name" name="month_name">
                                            <input type="hidden" class="form-control"
                                                   id="effect_year" name="effect_year">
                                            <div class="modal-body">
                                                <div class="modal-body">
                                                    <!--div class="form-group row">
                                                        <div class="col-sm-2">
                                                            <label for="month_name"
                                                                   class="control-label">เดือน</label>
                                                            <input type="text" class="form-control"
                                                                   id="month_name" name="month_name"
                                                                   readonly="true"
                                                                   placeholder="เดือน">
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <label for="effect_year"
                                                                   class="control-label">ปี</label>
                                                            <input type="text" class="form-control"
                                                                   id="effect_year"
                                                                   name="effect_year"
                                                                   required="required"
                                                                   readonly="true"
                                                                   placeholder="ปี">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                        </div>

                                                    </div-->

                                                    <table cellpadding="0" cellspacing="0" border="0"
                                                           class="display"
                                                           id="TableJobDetailList"
                                                           width="100%">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>วันที่</th>
                                                            <th>จำนวนพนักงานพันยาง</th>
                                                            <th>จำนวนยาง</th>
                                                            <th>ตะแนนเกรด</th>
                                                            <th>%รวมตามวัน</th>
                                                            <th>จำนวนเงินจ่ายรวม</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                    </table>

                                                </div>
                                            </div>

                                            <!--?php include("includes/stick_menu.php"); ?-->

                                            <div class="modal-footer">
                                                <input type="hidden" name="id" id="id"/>
                                                <input type="hidden" name="save_status" id="save_status"/>
                                                <input type="hidden" name="action" id="action"
                                                       value=""/>
                                                <button type="button" class="btn btn-danger"
                                                        id="btnClose">Close <i
                                                            class="fa fa-window-close"></i>
                                                </button>
                                            </div>
                                        </form>

                                        <div class="modal fade" id="recordModal">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Modal title</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×
                                                        </button>
                                                    </div>
                                                    <form method="post" id="recordForm">

                                                        <div class="form-group row">
                                                            <div class="col-sm-5">
                                                                <input type="hidden" class="form-control"
                                                                       id="KeyAddDetail"
                                                                       name="KeyAddDetail" value="">
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <input type="hidden" class="form-control"
                                                                       id="effect_month"
                                                                       name="effect_month" value="">
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <input type="hidden" class="form-control"
                                                                       id="effect_year"
                                                                       name="effect_year" value="">
                                                            </div>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="modal-body">

                                                                <div class="form-group row">
                                                                    <div class="col-sm-5">
                                                                        <label for="job_date"
                                                                               class="control-label">วันที่</label>
                                                                        <input type="job_date"
                                                                               class="form-control"
                                                                               id="job_date" name="job_date"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="วันที่">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-5">
                                                                        <label for="total_tires"
                                                                               class="control-label">จำนวนยาง</label>
                                                                        <input type="text" class="form-control"
                                                                               id="total_tires"
                                                                               name="total_tires"
                                                                               required="required"
                                                                               placeholder="จำนวนยาง">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-5">
                                                                        <label for="total_job_emp"
                                                                               class="control-label">จำนวนพนักงานพันยาง</label>
                                                                        <input type="text" class="form-control"
                                                                               id="total_job_emp"
                                                                               name="total_job_emp"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="จำนวนพนักงานพันยาง">
                                                                    </div>
                                                                    <div class="col-sm-5">
                                                                        <label for="total_grade_point"
                                                                               class="control-label">ตะแนนเกรด</label>
                                                                        <input type="text" class="form-control"
                                                                               id="total_grade_point"
                                                                               name="total_grade_point"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="ตะแนนเกรด">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-5">
                                                                        <label for="total_percent_payment"
                                                                               class="control-label">%รวมตามวัน</label>
                                                                        <input type="text" class="form-control"
                                                                               id="total_percent_payment"
                                                                               name="total_percent_payment"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="%รวมตามวัน">
                                                                    </div>
                                                                    <div class="col-sm-5">
                                                                        <label for="total_money"
                                                                               class="control-label">จำนวนเงินจ่ายรวม</label>
                                                                        <input type="text" class="form-control"
                                                                               id="total_money"
                                                                               name="total_money"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="จำนวนเงินจ่ายรวม">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id"/>
                                                            <input type="hidden" name="detail_id" id="detail_id"/>
                                                            <input type="hidden" name="action_detail"
                                                                   id="action_detail" value="UPDATE"/>
                                                            <span class="icon-input-btn">
                                                                <i class="fa fa-check"></i>
                                                            <input type="submit" name="save" id="save"
                                                                   class="btn btn-primary" value="Save"/>
                                                            </span>
                                                            <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Close <i
                                                                        class="fa fa-window-close"></i>
                                                            </button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>


                                        <div id="result"></div>

                                    </section>


                                </div>

                            </div>

                        </div>

                    </div>
                    <!--Row-->

                    <!-- Row -->

                </div>

                <!---Container Fluid-->

            </div>

            <?php
            include('includes/Modal-Logout.php');
            include('includes/Footer.php');
            ?>

        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Select2 -->
    <script src="vendor/select2/dist/js/select2.min.js"></script>


    <!-- Bootstrap Touchspin -->
    <script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
    <!-- ClockPicker -->

    <!-- RuangAdmin Javascript -->
    <script src="js/myadmin.min.js"></script>
    <script src="js/util.js"></script>
    <script src="js/Calculate.js"></script>
    <!-- Javascript for this page -->

    <script src="js/modal/show_supplier_modal.js"></script>
    <script src="js/modal/show_product_modal.js"></script>
    <script src="js/modal/show_unit_modal.js"></script>

    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css"/-->

    <script src="vendor/datatables/v11/bootbox.min.js"></script>
    <script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="vendor/datatables/v11/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="vendor/datatables/v11/buttons.dataTables.min.css"/>

    <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
    <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
    <!--link href="vendor/date-picker-1.9/css/date_picker_style.css" rel="stylesheet"/-->
    <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

    <style>

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

    <script>
        $(document).ready(function () {
            $('#effect_year').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $(".icon-input-btn").each(function () {
                let btnFont = $(this).find(".btn").css("font-size");
                let btnColor = $(this).find(".btn").css("color");
                $(this).find(".fa").css({'font-size': btnFont, 'color': btnColor});
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $("#btnClose").click(function () {
                window.opener = self;
                window.close();
            });
        });
    </script>

    <script type="text/javascript">
        let queryString = new Array();
        $(function () {
            if (queryString.length == 0) {
                if (window.location.search.split('?').length > 1) {
                    let params = window.location.search.split('?')[1].split('&');
                    for (let i = 0; i < params.length; i++) {
                        let key = params[i].split('=')[0];
                        let value = decodeURIComponent(params[i].split('=')[1]);
                        queryString[key] = value;
                    }
                }
            }

            let data = "<b>" + queryString["title"] + " " +  queryString["month_name"] + " " +  queryString["effect_year"] + "</b>";
            $("#title").html(data);
            $("#main_menu").html(queryString["main_menu"]);
            $("#sub_menu").html(queryString["sub_menu"]);
            $('#action').val(queryString["action"]);

            if (queryString["effect_month"] != null && queryString["effect_year"] != null) {

                $('#effect_month').val(queryString["effect_month"]);
                $('#month_name').val(queryString["month_name"]);
                $('#effect_year').val(queryString["effect_year"]);
                Load_Data_Detail(queryString["effect_month"], queryString["month_name"], queryString["effect_year"], "job_payment_daily_total");
            }
        });
    </script>

    <script>
        function Load_Data_Detail(effect_month, month_name, effect_year, table_name) {

            let formData = {
                action: "GET_JOB_DETAIL",
                sub_action: "GET_MASTER",
                effect_month: effect_month,
                month_name: month_name,
                effect_year: effect_year,
                table_name: table_name
            };
            let dataRecords = $('#TableJobDetailList').DataTable({
                "paging": false,
                "ordering": false,
                'info': false,
                "searching": false,
                'lengthMenu': [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
                'language': {
                    search: 'ค้นหา', lengthMenu: 'แสดง _MENU_ รายการ',
                    info: 'หน้าที่ _PAGE_ จาก _PAGES_',
                    infoEmpty: 'ไม่มีข้อมูล',
                    zeroRecords: "ไม่มีข้อมูลตามเงื่อนไข",
                    infoFiltered: '(กรองข้อมูลจากทั้งหมด _MAX_ รายการ)',
                    paginate: {
                        previous: 'ก่อนหน้า',
                        last: 'สุดท้าย',
                        next: 'ต่อไป'
                    }
                },
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'model/manage_job_detail_process.php',
                    'data': formData
                },
                'columns': [
                    {data: 'line_no'},
                    {data: 'job_date'},
                    {data: 'total_job_emp', className: "text-center"},
                    {data: 'total_tires', className: "text-right"},
                    {data: 'total_grade_point', className: "text-right"},
                    {data: 'total_percent_payment', className: "text-right"},
                    {data: 'total_money', className: "text-right"},
                    {data: 'update'}
                ]
            });
        }
    </script>

    <script>

        $("#TableJobDetailList").on('click', '.update', function () {

           let rec_id = $(this).attr("id");
           let table_name = "job_payment_daily_total";
           let formData = {action: "GET_DATA", id: rec_id, table_name: table_name};
            $.ajax({
                type: "POST",
                url: 'model/manage_job_detail_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let job_date = response[i].job_date;
                        let total_job_emp = response[i].total_job_emp;
                        let total_tires = response[i].total_tires;
                        let total_grade_point = response[i].total_grade_point;
                        let total_percent_payment = response[i].total_percent_payment;
                        let total_money = response[i].total_money;

                        $('#recordModal').modal('show');
                        $('#detail_id').val(id);
                        $('#job_date').val(job_date);
                        $('#total_job_emp').val(total_job_emp);
                        $('#total_tires').val(total_tires);
                        $('#total_grade_point').val(total_grade_point);
                        $('#total_percent_payment').val(total_percent_payment);
                        $('#total_money').val(total_money);
                        $('.modal-title').html("<i class='fa fa-plus'></i> Edit Record");
                        $('#action_detail').val('UPDATE');
                        $('#save').val('Save');
                    }
                },
                error: function (response) {
                    alertify.error("error : " + response);
                }
            });
        });

    </script>

    <script>

        $("#recordModal").on('submit', '#recordForm', function (event) {

            event.preventDefault();
            let formData = $(this).serialize();
            let effect_year = $('#effect_year').val();
            let effect_month = $('#effect_month').val();
            let month_name = $('#month_name').val();

            //alert(formData);

            $.ajax({
                url: 'model/manage_job_detail_process.php',
                method: "POST",
                data: formData,
                success: function (data) {
                    alertify.success(data);
                    $('#recordForm')[0].reset();
                    $('#recordModal').modal('hide');
                    $('#TableJobDetailList').DataTable().clear().destroy();
                    Load_Data_Detail(effect_month, month_name, effect_year, "job_payment_daily_total");
                }
            })

        });

    </script>

    <script>

        $('#total_tires,#total_grade_point,#total_percent_payment').blur(function () {

            let total_percent_payment = new Calculate($('#total_tires').val(), $('#total_grade_point').val());
            $('#total_percent_payment').val(total_percent_payment.Multiple().toFixed(2));

        });

    </script>

    </body>

    </html>

<?php } ?>



