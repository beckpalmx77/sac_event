<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    ?>

    <!doctype html>
    <html>
    <head lang="en">
        <meta charset="utf-8">
        <title>สงวนออโต้คาร์</title>
        <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
              crossorigin="anonymous">
    </head>
    <body>
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

                <div class="col-md-8">

                    <form method="post" id="recordForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="modal-body">


                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="text"
                                               class="control-label">เลขที่เอกสาร</label>
                                        <input type="text" class="form-control"
                                               id="doc_id" name="doc_id"
                                               readonly="true"
                                               required="required"
                                               value=""
                                               placeholder="">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="doc_date"
                                               class="control-label">วันที่เอกสาร</label>
                                        <i class="fa fa-calendar"
                                           aria-hidden="true"></i>
                                        <input type="text" class="form-control"
                                               id="doc_date"
                                               name="doc_date"
                                               required="required"
                                               value=""
                                               readonly="true"
                                               placeholder="วันที่เอกสาร">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="text"
                                               class="control-label">รหัสพนักงาน</label>
                                        <input type="text" class="form-control"
                                               id="emp_id" name="emp_id"
                                               readonly="true"
                                               required="required"
                                               value=""
                                               placeholder="">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="text"
                                               class="control-label">ชื่อ -
                                            นามสกุล</label>
                                        <input type="text" class="form-control"
                                               id="full_name" name="full_name"
                                               readonly="true"
                                               value=""
                                               placeholder="">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <input type="hidden" class="form-control"
                                           id="leave_type_id"
                                           required="required"
                                           name="leave_type_id">
                                    <div class="col-sm-10">
                                        <label for="leave_type_detail"
                                               class="control-label">ประเภทการลา</label>
                                        <input type="text" class="form-control"
                                               id="leave_type_detail"
                                               name="leave_type_detail"
                                               required="required"
                                               readonly="true"
                                               placeholder="">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="date_leave_start"
                                               class="control-label">วันที่ลาเริ่มต้น</label>
                                        <i class="fa fa-calendar"
                                           aria-hidden="true"></i>
                                        <input type="text" class="form-control"
                                               id="date_leave_start"
                                               name="date_leave_start"
                                               value=""
                                               required="required"
                                               readonly="true"
                                               placeholder="วันที่ลาเริ่มต้น">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="date_leave_start"
                                               class="control-label">เวลาเริ่มต้น</label>
                                        <input type="text" class="form-control"
                                               id="time_leave_start"
                                               name="time_leave_start"
                                               value=""
                                               required="required"
                                               placeholder="hh:mm">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="date_leave_start"
                                               class="control-label">วันที่ลาสิ้นสุด</label>
                                        <i class="fa fa-calendar"
                                           aria-hidden="true"></i>
                                        <input type="text" class="form-control"
                                               id="date_leave_to"
                                               name="date_leave_to"
                                               value=""
                                               required="required"
                                               readonly="true"
                                               placeholder="วันที่ลาสิ้นสุด">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="time_leave_to"
                                               class="control-label">เวลาสิ้นสุด</label>
                                        <input type="text" class="form-control"
                                               id="time_leave_to"
                                               name="time_leave_to"
                                               required="required"
                                               value=""
                                               placeholder="hh:mm">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <input id="uploadImage" type="file" accept="image/*" name="image"/>

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

        </form>

        <div id="err"></div>

    </div>
    </div>
    </div>
    </div>

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

            let data = "<b>" + queryString["title"] + "</b>";
            $("#title").html(data);
            $("#main_menu").html(queryString["main_menu"]);
            $("#sub_menu").html(queryString["sub_menu"]);
            $('#action').val(queryString["action"]);

            if (queryString["id"] != null && queryString["doc_id"] != null) {

                $('#id').val(queryString["id"]);
                $('#doc_id').val(queryString["doc_id"]);
                $('#doc_date').val(queryString["doc_date"]);
                $('#emp_id').val(queryString["emp_id"]);
                $('#full_name').val(queryString["full_name"]);
                $('#date_leave_start').val(queryString["date_leave_start"]);
                $('#date_leave_to').val(queryString["date_leave_to"]);


            }
        });
    </script>

    <script>

        $(document).ready(function (e) {
            $("#form").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                    url: "upload_ajax.php",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
//$("#preview").fadeOut();
                        $("#err").fadeOut();
                    },
                    success: function (data) {
                        if (data == 'invalid') {
// invalid file format.
                            $("#err").html("Invalid File !").fadeIn();
                        } else {
// view uploaded file.
                            $("#preview").html(data).fadeIn();
                            $("#form")[0].reset();
                        }
                    },
                    error: function (e) {
                        $("#err").html(e).fadeIn();
                    }
                });
            }));
        });
    </script>

    </body>
    </html>

    <?php
}
?>

