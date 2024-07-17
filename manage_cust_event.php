<?php
session_start();
error_reporting(0);
include('includes/Header.php');
include('config/connect_db.php');
$curr_date = date("d-m-Y");

if (strlen($_SESSION['alogin']) == "" || strlen($_SESSION['province_name']) == "") {
    header("Location: index.php");
} else {
    ?>

    <!DOCTYPE html>
    <html lang="th">
    <body id="page-top">
    <div id="wrapper">
        <?php
        include('includes/Side-Bar.php');
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                include('includes/Top-Bar.php');
                ?>
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo urldecode($_GET['s']) ?></h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a>
                            </li>
                            <li class="breadcrumb-item"><?php echo urldecode($_GET['m']) ?></li>
                            <li class="breadcrumb-item active"
                                aria-current="page"><?php echo urldecode($_GET['s']) ?></li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-12">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                </div>
                                <div class="card-body">
                                    <section class="container-fluid">

                                        <div class="col-md-12 col-md-offset-2">
                                            <label for="name_t"
                                                   class="control-label"><b>เพิ่ม <?php echo urldecode($_GET['s']) ?></b></label>
                                            <button type='button' name='btnAdd' id='btnAdd'
                                                    class='btn btn-primary btn-xs'>Add
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <div class="col-md-12 col-md-offset-2">
                                            <table id='TableRecordList' class='display dataTable'>
                                                <thead>
                                                <tr>
                                                    <th>รหัสลูกค้า</th>
                                                    <th>ชื่อบริษัท/ร้านค้า</th>
                                                    <th>ชื่อผู้ติดต่อ</th>
                                                    <th>หมายเลขโทรศัพท์</th>
                                                    <th>จังหวัด</th>
                                                    <th>sale/ผู้ติดต่อ</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>รหัสลูกค้า</th>
                                                    <th>ชื่อบริษัท/ร้านค้า</th>
                                                    <th>ชื่อผู้ติดต่อ</th>
                                                    <th>หมายเลขโทรศัพท์</th>
                                                    <th>จังหวัด</th>
                                                    <th>sale/ผู้ติดต่อ</th>
                                                    <th>Action</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <div id="result"></div>

                                        </div>

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
                                                        <div class="modal-body">
                                                            <div class="modal-body">

                                                                <div class="form-group">
                                                                    <label for="text"
                                                                           class="control-label">รหัสลูกค้า</label>
                                                                    <input type="cust_id" class="form-control"
                                                                           id="cust_id" name="cust_id"
                                                                           placeholder="รหัสลูกค้า">
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <label for="ar_name"
                                                                               class="control-label">ชื่อบริษัท/ร้านค้า</label>
                                                                        <input type="text" class="form-control"
                                                                               id="ar_name"
                                                                               name="ar_name"
                                                                               required="required"
                                                                               placeholder="">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label for="cust_name_1"
                                                                               class="control-label">ชื่อผู้ติดต่อ</label>
                                                                        <input type="text" class="form-control"
                                                                               id="cust_name_1"
                                                                               name="cust_name_1"
                                                                               required="required"
                                                                               placeholder="">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label for="phone"
                                                                               class="control-label">หมายเลขโทรศัพท์</label>
                                                                        <input type="text" class="form-control"
                                                                               id="phone"
                                                                               name="phone"
                                                                               required="required"
                                                                               placeholder="จังหวัด">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <input type="hidden" class="form-control"
                                                                           id="province_code"
                                                                           name="province_code">
                                                                    <div class="col-sm-10">
                                                                        <label for="province_name"
                                                                               class="control-label">จังหวัด</label>
                                                                        <input type="text" class="form-control"
                                                                               id="province_name"
                                                                               name="province_name"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="จังหวัด">
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <label for="province_name"
                                                                               class="control-label">เลือก</label>
                                                                        <a data-toggle="modal"
                                                                           href="#SearchProvinceModal"
                                                                           class="btn btn-primary">
                                                                            Click <i class="fa fa-search"
                                                                                     aria-hidden="true"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id"/>
                                                            <input type="hidden" name="action" id="action" value=""/>
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


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="SearchProvinceModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Modal title</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×
                                    </button>
                                </div>
                                <div class="container"></div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <table cellpadding="0" cellspacing="0" border="0"
                                               class="display"
                                               id="TableProvinceList"
                                               width="100%">
                                            <thead>
                                            <tr>
                                                <th>รหัสจังหวัด</th>
                                                <th>ชื่อจังหวัด</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>รหัสจังหวัด</th>
                                                <th>ชื่อจังหวัด</th>
                                                <th>Action</th>
                                            </tr>
                                            </tfoot>
                                        </table>
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
    include('includes/Footer.php');
    ?>


    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/myadmin.min.js"></script>

    <script src="js/util/calculate_datetime.js"></script>

    <!--script src="js/modal/show_evs_province_modal.js"></script-->

    <!-- Page level plugins -->

    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css"/-->

    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
    <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
    <!--link href="vendor/date-picker-1.9/css/date_picker_style.css" rel="stylesheet"/-->
    <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

    <script src="vendor/datatables/v11/bootbox.min.js"></script>
    <script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="vendor/datatables/v11/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="vendor/datatables/v11/buttons.dataTables.min.css"/>


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
            $(".icon-input-btn").each(function () {
                let btnFont = $(this).find(".btn").css("font-size");
                let btnColor = $(this).find(".btn").css("color");
                $(this).find(".fa").css({'font-size': btnFont, 'color': btnColor});
            });
        });
    </script>

    <script>
        $(document).ready(function () {

            let search_province = $('#search_province').val();
            let searchName = $('#search_name').val();
            let searchContact = $('#search_contact').val();

            let formData = {action: "GET_CUSTOMER", sub_action: "GET_MASTER", page_manage: "ADMIN",search_province: search_province,searchName: searchName,searchContact: searchContact};
            let dataRecords = $('#TableRecordList').DataTable({
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
                    'url': 'model/manage_cust_event_process.php',
                    'data': formData,
                },
                'columns': [
                    {data: 'cust_id'},
                    {data: 'ar_name'},
                    {data: 'cust_name_1'},
                    {data: 'phone'},
                    {data: 'province_name'},
                    {data: 'sale_contact_name'},
                    {data: 'update'}
                ]
            });

            <!-- *** FOR SUBMIT FORM *** -->
            $("#recordModal").on('submit', '#recordForm', function (event) {
                event.preventDefault();
                $('#save').attr('disabled', 'disabled');
                let formData = $(this).serialize();
                $.ajax({
                    url: 'model/manage_cust_event_process.php',
                    method: "POST",
                    data: formData,
                    success: function (data) {
                        alertify.success(data);
                        $('#recordForm')[0].reset();
                        $('#recordModal').modal('hide');
                        $('#save').attr('disabled', false);
                        dataRecords.ajax.reload();
                    }
                })
            });
            <!-- *** FOR SUBMIT FORM *** -->
        });
    </script>

    <script>

        $("#TableRecordList").on('click', '.update', function () {
            let id = $(this).attr("id");
            //alert(id);
            let formData = {action: "GET_DATA", id: id};
            $.ajax({
                type: "POST",
                url: 'model/manage_cust_event_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let cust_id = response[i].cust_id;
                        let ar_name = response[i].ar_name;
                        let cust_name_1 = response[i].cust_name_1;
                        let cust_name_2 = response[i].cust_name_2;
                        let cust_name3 = response[i].cust_name3;
                        let cust_name4 = response[i].cust_name4;
                        let cust_name5 = response[i].cust_name5;
                        let cust_name6 = response[i].cust_name6;
                        let phone = response[i].phone;
                        let province_name = response[i].province_name;
                        let sale_contact_name = response[i].sale_contact_name;

                        $('#recordModal').modal('show');
                        $('#id').val(id);
                        $('#cust_id').val(cust_id);
                        $('#ar_name').val(ar_name);
                        $('#cust_name_1').val(cust_name_1);
                        $('#phone').val(phone);
                        $('#province_name').val(province_name);
                        $('#sale_contact_name').val(sale_contact_name);
                        $('.modal-title').html("<i class='fa fa-plus'></i> Edit Record");
                        $('#action').val('UPDATE');
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
        $(document).ready(function () {
            let formData = {action: "GET_PROVINCE", sub_action: "GET_SELECT"};
            let dataRecords = $('#TableProvinceList').DataTable({
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
                    'url': 'model/manage_evs_province_process.php',
                    'data': formData
                },
                'columns': [
                    {data: 'province_code'},
                    {data: 'province_name'},
                    {data: 'select'}
                ]
            });
        });

        $("#TableProvinceList").on('click', '.select', function () {
            let data = this.id.split('@');
            $('#province_code').val(data[0]);
            $('#province_name').val(data[1]);
            $('#SearchProvinceModal').modal('hide');
        });

    </script>


    </body>
    </html>

<?php } ?>