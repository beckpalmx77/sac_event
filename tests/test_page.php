<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>สงวนออโต้คาร์</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <link href="https://app.sanguanautocar.co.th/css/style_layout.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://app.sanguanautocar.co.th/css/lightpick.css">
    <link rel="stylesheet" type="text/css" href="https://app.sanguanautocar.co.th/css/all.css">

    <!-- DataTable Bootstrap -->
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"></head>

<body class="layout fixed ">

<!-- Left side column. contains the logo and sidebar -->
<header class="main-header visible-xs">
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="color: tomato;">
            <span style="
                    width: 21px;
                    height: 19px;
                    background-color: #263d90;
                    position: absolute;
                    top: 15px;
                    z-index: -1;
                    left: 10px;
                    border-radius: 5px;
                "></span>
            <span class="sr-only">Toggle navigation</span>
        </a>
    </nav>
</header>

<aside class="main-sidebar silde_menu" id="sidebar-wrapper">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="text-center">
                <img src="https://app.sanguanautocar.co.th/images/sac-logo.png" width="150" height="150">

            </div>

        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="active">
                <a href="/dashboard">
                    <span>ภาพรวมทั้งหมด</span>
                </a>
            </li>

            <li class="">
                <a href="https://app.sanguanautocar.co.th/orders">
                    <span>รายการสั่งซื้อ</span>
                </a>
            </li>
            <li class="">
                <a href="https://app.sanguanautocar.co.th/docinfos">
                    <span>รายการค้างชำระ</span>
                </a>
            </li>

            <li class="">
                <a href="https://app.sanguanautocar.co.th/stocks">
                    <span>จัดการคลังสินค้า</span>
                </a>
            </li>

            <li class="">
                <a href="https://app.sanguanautocar.co.th/customers">
                    <span>รายชื่อข้อมูลลูกค้า</span>
                </a>
            </li>

            <li class="">
                <a href="https://app.sanguanautocar.co.th/users">
                    <span>จัดการผู้ใช้งาน</span>
                </a>
            </li>

            <li class="treeview ">
                <a href="#">
                    <span>ตั้งค่า</span>
                    <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu sidebar-menu">
                    <li class="">
                        <a href="https://app.sanguanautocar.co.th/setting/brands">
                            <span>ยี่ห้อสินค้า</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="https://app.sanguanautocar.co.th/setting/generations">
                            <span>รุ่นสินค้า</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="https://app.sanguanautocar.co.th/setting/transports">
                            <span>การขนส่ง</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="https://app.sanguanautocar.co.th/setting/payment-types">
                            <span>รูปแบบการชำระเงิน</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="https://app.sanguanautocar.co.th/setting/price-groups">
                            <span>กลุ่มการชำระเงิน</span>
                        </a>
                    </li>
                </ul>
            </li>




            <!-- Menu Footer-->
            <div class="user-footer">
                <div class="row pull-left">
                    <p style="margin-left:30px;margin-top:5px">
                        <img src="https://app.sanguanautocar.co.th/images/no-image.png" width="30px;"
                             class="img-circle" alt=""/>

                        หนุ่ม IT SAC
                    </p>
                </div>

                <div class="pull-right">
                    <a href="https://app.sanguanautocar.co.th/logout" class=""
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt" style="font-size:25px; margin:10px; color:#263d90;"></i>
                    </a>
                    <form id="logout-form" action="https://app.sanguanautocar.co.th/logout" method="POST" style="display: none;">
                        <input type="hidden" name="_token" value="IibEBlzvOgvjHwFOwqcXlOpmQv7QojHu0MHvgwMF">
                    </form>
                </div>

            </div>

        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="padding-top:10px">
    <div class="visible-xs" style="height: 20px;"></div>
    <section class="content-header">
        <h1 class="pull-left" style="color:#263d90;font-weight: bold;">ภาพรวมทั้งหมด</h1>
        <h1 class="pull-right">

            <div id="type_btn" class="btn-group btn-group-toggle btn-select-order" data-toggle="buttons">
                <label class="btn btn-white btn-sm   ">
                    <input type="radio" name="type_date" id="type_date1" autocomplete="off" value="today" > วันที่
                </label>
                <label class="btn btn-white btn-sm active">
                    <input type="radio" name="type_date" id="type_date2" autocomplete="off" value="week" > สัปดาห์
                </label>
                <label class="btn btn-white btn-sm  " >
                    <input type="radio" name="type_date" id="type_date3" autocomplete="off" value="month" > เดือน
                </label>
                <label class="btn btn-white btn-sm  ">
                    <input type="radio" name="type_date" id="type_date4" autocomplete="off" value="year" > ปี
                </label>
            </div>
        </h1>
    </section>



    <script type="text/javascript">
        setTimeout(function(){
            $('#type_btn .btn').change(function(){
                var type = $("#type_btn input[type='radio']:checked").val();
                window.location.href = "https://app.sanguanautocar.co.th/dashboard?date="+type;
            });

        }, 500);

    </script>
    <br/>
    <div class="content">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-8">
                <div class="panel">
                    <div class="container-fluid ">
                        <h4 style="color:#263d90"><i class="fa fa-chart-bar"></i> ยอดการสั่งซื้อ</h4>
                        <hr>
                        <center>
                            <div style="width:100%;">
                                <canvas id="barChartDashboard" width="400" height="200">
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function(event) {
                                            (function() {
                                                "use strict";
                                                var ctx = document.getElementById("barChartDashboard");
                                                window.barChartDashboard = new Chart(ctx, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: [],
                                                        datasets: [{"backgroundColor":"rgb(216, 105, 65)"}]
                                                    },
                                                    options: {"legend":{"display":false},"scales":{"xAxes":[{"stacked":true,"gridLines":{"display":false}}],"yAxes":[{"ticks":{"beginAtZero":true}}]}}
                                                });
                                            })();
                                        });
                                    </script>
                                </canvas>

                            </div>
                        </center>

                    </div>


                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel">
                    <div class="container-fluid ">
                        <h4 style="color:#263d90"><i class="fa fa-money-bill-alt"></i> ยอดขายและสินค้าขายดี</h4>
                        <hr>

                        <h1 style="color:tomato"><i class="fa fa-dollar-sign"></i> 0</h1>
                        <p>ยอดขายรวม วันที่ ( 22/07/24 - 29/07/24)</p>


                        <table style="width:100%;" id="datatable_money" class="table no-footer">
                            <tr>
                                <th>ชื่อสินค้า</th>
                                <th>ยอดขาย</th>
                            </tr>

                        </table>


                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel">
            <div class="box-body">
                <h4 style="color:#263d90"><i class="fa fa-file-alt" style="font-size:25px"></i> รายการสั่งซื้อทั้งหมด</h4>
                <hr>
                <div class="table-responsive">

                    <table  class="table" id="dataTableBuilder" width="100%"><thead><tr><th  title="หมายเลขคำสั่งซื้อ" class="table_col ">หมายเลขคำสั่งซื้อ</th><th  title="ลูกค้า" class="table_col">ลูกค้า</th><th  title="ออเดอร์" class="table_col">ออเดอร์</th><th  title="ชำระเงิน" class="table_col">ชำระเงิน</th><th  title="วันที่สั่ง" class="table_col">วันที่สั่ง</th><th  title="จำนวน" class="table_col">จำนวน</th><th  title="ยอดรวม">ยอดรวม</th><th  title="" width="50px"></th></tr></thead></table>
                </div>

            </div>
        </div>
    </div>


</div>

<!-- jQuery 3.1.1 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://app.sanguanautocar.co.th/js/lightpick.js"></script>

<!-- Datatables -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.js"></script>
<script src="https://app.sanguanautocar.co.th/vendor/datatables/buttons.server-side.js"></script>    <script>

    // call function transfer
    function transfer_doc (id) {
        $.ajax({
            type: 'GET',
            url: "https://app.sanguanautocar.co.th/tranfer/" + id,
            success: function (res) {
                // call back
                if (res) {
                    console.log(res)
                }
            }
        })
    }

    //call fucntion Certificate
    function transfer_cert (id) {
        $.ajax({
            type: 'GET',
            url: "https://app.sanguanautocar.co.th/certificate/" + id,
            success: function (res) {
                // call back
                if (res) {
                    console.log(res)
                }
            }
        })
    }
</script>
<script type="text/javascript">$(function(){window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["dataTableBuilder"]=$("#dataTableBuilder").DataTable({"serverSide":true,"processing":true,"ajax":{"url":"https:\/\/app.sanguanautocar.co.th","type":"GET","data":function(data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (!data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                delete data.search.regex;}},"columns":[{"name":"id","data":"id","title":"\u0e2b\u0e21\u0e32\u0e22\u0e40\u0e25\u0e02\u0e04\u0e33\u0e2a\u0e31\u0e48\u0e07\u0e0b\u0e37\u0e49\u0e2d","class":"table_col ","orderable":true,"searchable":true},{"name":"customer_id","data":"customer_id","title":"\u0e25\u0e39\u0e01\u0e04\u0e49\u0e32","class":"table_col","orderable":true,"searchable":true},{"name":"status","data":"status","title":"\u0e2d\u0e2d\u0e40\u0e14\u0e2d\u0e23\u0e4c","class":"table_col","orderable":true,"searchable":true},{"name":"payment_status","data":"payment_status","title":"\u0e0a\u0e33\u0e23\u0e30\u0e40\u0e07\u0e34\u0e19","class":"table_col","orderable":true,"searchable":true},{"name":"date","data":"date","title":"\u0e27\u0e31\u0e19\u0e17\u0e35\u0e48\u0e2a\u0e31\u0e48\u0e07","class":"table_col","orderable":true,"searchable":true},{"name":"discounts","data":"discounts","title":"\u0e08\u0e33\u0e19\u0e27\u0e19","class":"table_col","orderable":true,"searchable":true},{"name":"prices_net","data":"prices_net","title":"\u0e22\u0e2d\u0e14\u0e23\u0e27\u0e21","orderable":true,"searchable":true},{"defaultContent":"","data":"action","name":"action","title":"","render":null,"orderable":false,"searchable":false,"width":"50px"}],"dom":"Bfrtip","order":[[0,"desc"]],"buttons":[],"oLanguage":{"oPaginate":{"sPrevious":"<","sNext":">","sFirst":"<<","sLast":">>"},"sSearch":"\u0e04\u0e49\u0e19\u0e2b\u0e32","sEmptyTable":"\u0e44\u0e21\u0e48\u0e1e\u0e1a\u0e02\u0e49\u0e2d\u0e21\u0e39\u0e25","sZeroRecords":"\u0e44\u0e21\u0e48\u0e1e\u0e1a\u0e02\u0e49\u0e2d\u0e21\u0e39\u0e25"}});});
</script>






<script>
    function copyToClipboard (element) {
        var $temp = $('<input>')
        $('body').append($temp)
        $temp.val($(element).html()).select()
        document.execCommand('copy')
        $temp.remove()
    }
</script>
</body>
</html>

