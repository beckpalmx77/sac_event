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
                                            <div class="modal-body">
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-3">
                                                            <!--label for="job_date"
                                                                   class="control-label">วันที่</label-->
                                                            <input type="hidden" class="form-control"
                                                                   id="job_date"
                                                                   name="job_date"
                                                                   required="required"
                                                                   readonly="true"
                                                                   placeholder="วันที่">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <table cellpadding="0" cellspacing="0" border="0"
                                                           class="display"
                                                           id="TableJobDetailList"
                                                           width="100%">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>วันที่</th>
                                                            <th>ชื่อพนักงาน</th>
                                                            <th>เกรด</th>
                                                            <th>คะแนนเกรด</th>
                                                            <th>%ได้เงิน</th>
                                                            <th>จำนวนเงิน</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                    </table>

                                                    <input type="hidden" id="status" name="status" value="">

                                                </div>
                                            </div>

                                            <!--?php include("includes/stick_menu.php"); ?-->

                                            <div class="modal-footer">

                                                <input type="hidden" name="id" id="id"/>
                                                <input type="hidden" name="save_status" id="save_status"/>
                                                <input type="hidden" name="action" id="action"
                                                       value=""/>
                                                <!--button type="button" class="btn btn-primary"
                                                        id="btnSave">Save <i
                                                            class="fa fa-check"></i>
                                                </button-->
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
                                                                <input type="hidden" class="form-control"
                                                                       id="emp_id"
                                                                       name="emp_id" value="">
                                                            </div>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="modal-body">

                                                                <div class="form-group row">
                                                                    <div class="col-sm-5">
                                                                        <label for="job_date_trans"
                                                                               class="control-label">วันที่</label>
                                                                        <input type="text"
                                                                               class="form-control"
                                                                               id="job_date_trans" name="job_date_trans"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="วันที่">
                                                                    </div>
                                                                    <div class="col-sm-5">
                                                                        <label for="product_id"
                                                                               class="control-label">ชื่อพนักงาน</label>
                                                                        <input type="f_name"
                                                                               class="form-control"
                                                                               id="f_name" name="f_name"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="ชื่อพนักงาน">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-5">
                                                                        <label for="grade_point"
                                                                               class="control-label">เกรด (A,B,C)</label>
                                                                        <input type="text" class="form-control"
                                                                               id="grade_point"
                                                                               name="grade_point"
                                                                               placeholder="เกรด">
                                                                    </div>
                                                                    <div class="col-sm-5">
                                                                        <label for="total_grade_point"
                                                                               class="control-label">คะแนนเกรด</label>
                                                                        <input type="text" class="form-control"
                                                                               id="total_grade_point"
                                                                               name="total_grade_point"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="คะแนนเกรด">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-5">
                                                                        <label for="price"
                                                                               class="control-label">%เงินได้</label>
                                                                        <input type="text" class="form-control"
                                                                               id="total_percent_payment"
                                                                               name="total_percent_payment"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="%เงินได้">
                                                                    </div>
                                                                    <div class="col-sm-5">
                                                                        <label for="total_price"
                                                                               class="control-label">จำนวนเงิน</label>
                                                                        <input type="text" class="form-control"
                                                                               id="total_money"
                                                                               name="total_money"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="จำนวนเงิน">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id"/>
                                                            <input type="hidden" name="detail_id" id="detail_id"/>
                                                            <input type="hidden" name="action_detail"
                                                                   id="action_detail" value=""/>

                                                            <input type="hidden" name="effect_month" id="effect_month"/>
                                                            <input type="hidden" name="effect_year" id="effect_year"/>

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
            $('#doc_date').datepicker({
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
                if ($('#save_status').val() !== '') {
                    window.opener = self;
                    window.close();
                } else {
                    alertify.error("กรุณากด save อีกครั้ง");
                }
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

            let title = queryString["title"] + " :  " + queryString["job_date"];

            let data = "<b>" + title + "</b>";
            $("#title").html(data);
            $("#main_menu").html(queryString["main_menu"]);
            $("#sub_menu").html(queryString["sub_menu"]);
            $('#job_date').val(queryString["job_date"]);

            $('#save_status').val("before");

            if (queryString["action"] === 'ADD') {
                let KeyData = generate_token(15);
                $('#KeyAddData').val(KeyData + ":" + Date.now());
                $('#save_status').val("add");
            }

            if (queryString["job_date"] != null) {
                Load_Header(queryString["job_date"], "job_payment_daily_total");
            }

            Load_Data_Detail(queryString["job_date"], "job_payment_daily_total");
            //alert("job_date = " + $('#job_date').val());

        });
    </script>

    <script>
        function Load_Data_Detail(job_date, table_name) {

            let formData = {
                action: "GET_JOB_DETAIL",
                sub_action: "GET_MASTER",
                job_date: job_date,
                table_name: table_name
            };
            let dataRecords = $('#TableJobDetailList').DataTable({
                "paging": true,
                "ordering": true,
                'info': true,
                "searching": true,
                'lengthMenu': [[18, 24, 50, 100], [18, 24, 50, 100]],
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
                    'url': 'model/manage_job_calendar_process.php',
                    'data': formData
                },
                'columns': [
                    {data: 'line_no'},
                    {data: 'job_date'},
                    {data: 'f_name'},
                    {data: 'grade_point'},
                    {data: 'total_grade_point', className: "text-right"},
                    {data: 'total_percent_payment', className: "text-right"},
                    {data: 'total_money', className: "text-right"},
                    {data: 'update'}
                ]
            });
        }
    </script>

    <script>

        function Load_Header(job_date, table_name) {

            //alert(id);
            let $table_name = table_name;
            let formData = {action: "GET_JOB_DATA", job_date: job_date};
            $.ajax({
                type: "POST",
                url: 'model/manage_job_calendar_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let job_date = response[i].job_date;

                        $('#id').val(id);
                        $('#job_date').val(job_date);

                    }
                },
                error: function (response) {
                    alertify.error("error : " + response);
                }
            });

        }

    </script>

    <script>

        $("#TableJobDetailList").on('click', '.update', function () {
            let id = $(this).attr("id");
            //alert(id);
            let formData = {action: "GET_JOB_TRANS_DATA", id: id};
            $.ajax({
                type: "POST",
                url: 'model/manage_job_calendar_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let detail_id = response[i].id;
                        let job_date_trans = response[i].job_date;
                        let emp_id = response[i].emp_id;
                        let f_name = response[i].f_name;
                        let grade_point = response[i].grade_point;
                        let total_grade_point = response[i].total_grade_point;
                        let total_percent_payment = response[i].total_percent_payment;
                        let total_money = response[i].total_money;
                        let effect_month = response[i].effect_month;
                        let effect_year = response[i].effect_year;

                        $('#recordModal').modal('show');
                        $('#detail_id').val(detail_id);
                        $('#job_date_trans').val(job_date_trans);
                        $('#emp_id').val(emp_id);
                        $('#f_name').val(f_name);
                        $('#grade_point').val(grade_point);
                        $('#total_grade_point').val(total_grade_point);
                        $('#total_percent_payment').val(total_percent_payment);
                        $('#total_money').val(total_money);
                        $('#effect_month').val(effect_month);
                        $('#effect_year').val(effect_year);
                        $('.modal-title').html("<i class='fa fa-plus'></i> Edit Record");
                        $('#action').val('UPDATE');
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
            //$('#save').attr('disabled', 'disabled');
            let formData = $(this).serialize();
            let job_date_trans = $('#job_date_trans').val();
            let table_name = "v_job_transaction";

            //alert(formData);

            $.ajax({
                url: 'model/manage_job_calendar_process.php',
                method: "POST",
                data: formData,
                success: function (data) {
                    //alertify.success(data);
                    //$('#recordForm')[0].reset();
                    //$('#recordModal').modal('hide');
                    //$('#save').attr('disabled', false);
                    //dataRecords.ajax.reload();

                    alertify.success(data);
                    $('#recordForm')[0].reset();
                    $('#recordModal').modal('hide');
                    $('#TableJobDetailList').DataTable().clear().destroy();
                    Load_Data_Detail(job_date_trans, table_name);

                }
            })

        });

    </script>


    </body>

    </html>

<?php } ?>



