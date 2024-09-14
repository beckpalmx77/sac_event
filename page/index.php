<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>บริษัท สงวนออโต้คาร์ จำกัด</title>

    <!-- CSS -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="img/logo.png">
</head>

<body>

<!-- Contact Form -->
<div class="c-form-container section-container section-container-image-bg">
    <div class="container">
        <div><img src="img/logo text-01.png" width="250" height="129"/></div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 c-form-box wow fadeInUp">

                <div class="c-form-top">
                    <div class="c-form-top-left">
                        <h3>ค้นหาข้อมูล เข้าร่วมงาน</h3>
                        <p>กรอก หมายเลขโทรศัพท์ เพื่อค้นหาข้อมูล</p>
                    </div>
                    <div class="c-form-top-right">
                        <div class="c-form-top-right-icon">
                            <i class="fa fa-heart" style="color: #0F9347;"></i>
                        </div>
                    </div>
                </div>

                <div class="c-form-bottom">
                    <form id="searchForm">
                        <div class="form-group">
                            <label for="c-form-name">
                                <!--span class="label-text">ชื่อ หรือ หมายเลขโทรศัพท์ :</span-->
                                <span class="contact-error"></span>
                            </label>
                            <input type="text" id="searchText" name="searchText" placeholder="หมายเลขโทรศัพท์"
                                   class="c-form-name form-control">
                        </div>
                        <!-- ใช้ปุ่มแบบ Bootstrap -->
                        <button type="button" id="searchButton" class="btn btn-success">ค้นหา</button>
                    </form>
                    <div id="result"></div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Javascript -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.backstretch.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/scripts.js"></script>

<!--[if lt IE 10]>
<script src="assets/js/placeholder.js"></script>
<![endif]-->

<script>
    $(document).ready(function() {
        $('#searchButton').on('click', function() {
            let query = $('#searchText').val();
            if (query.length >= 2) { // ค้นหาหลังจากพิมพ์เกิน 2 ตัวอักษร
                $.ajax({
                    url: 'search.php',
                    method: 'POST',
                    data: {query: query},
                    success: function(data) {
                        $('#result').html(data);
                    }
                });
            } else {
                $('#result').html(''); // ล้างผลลัพธ์เมื่อไม่มีคำค้นหา
            }
        });
    });
</script>

</body>

</html>