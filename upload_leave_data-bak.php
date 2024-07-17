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
        <title>Ajax File Upload with jQuery and PHP</title>
        <link rel="stylesheet" href="style.css" type="text/css"/>
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

                    <form id="form" action="upload_ajax.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="id" name="id" value="">
                        <div class="col-sm-6">
                            <label for="doc_id"
                                   class="control-label">เลขที่เอกสาร</label>
                            <input type="text" class="form-control"
                                   id="doc_id"
                                   name="doc_id"
                                   value=""
                                   required="required"
                                   placeholder="">
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
                                   value="<?php //echo $curr_date ?>"
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
                                   value="<?php echo $_SESSION['work_time_stop'] ?>"
                                   placeholder="hh:mm">
                        </div>
                </div>

                        <input id="uploadImage" type="file" accept="image/*" name="image"/>
                        <div id="preview"><img src="filed.png"/></div>

                        <br>

                        <input class="btn btn-success" type="submit" value="Upload">

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
                $('#date_leave_start').val(queryString["date_leave_start"]);


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

