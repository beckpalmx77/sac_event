<?php
// Example data - replace with your actual data
$items = [
    ['description' => 'Item 1', 'quantity' => 2, 'price' => 20],
    ['description' => 'Item 2', 'quantity' => 1, 'price' => 50],
    ['description' => 'Item 3', 'quantity' => 3, 'price' => 15],
];
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link href="../img/logo/logo.png" rel="icon">
    <title>สงวนออโต้คาร์ | SANGUAN AUTO CAR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .slip-container {
            width: 300px;
            margin: auto;
            border: 1px solid #000;
            padding: 10px;
        }
        .slip-header, .slip-footer {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .slip-footer {
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        .slip-body {
            margin-bottom: 10px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }
        .large-text {
            font-size: 18px; /* ปรับขนาดฟอนต์ตามต้องการ */
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };

        function getQueryString() {
            const queryString = {};
            if (window.location.search) {
                const params = window.location.search.substring(1).split('&');
                for (let i = 0; i < params.length; i++) {
                    const [key, value] = params[i].split('=');
                    queryString[decodeURIComponent(key)] = decodeURIComponent(value);
                }
            }
            return queryString;
        }

        window.addEventListener('DOMContentLoaded', (event) => {
            const queryString = getQueryString();
            if (Object.keys(queryString).length > 0) {
                document.getElementById('ar_name').textContent = queryString.ar_name || '-';
                document.getElementById('table_number').textContent = "โต๊ะหมายเลข : " + (queryString.table_number || '-');
                document.getElementById('phone').textContent = queryString.phone || '-';
            }
        });
    </script>
</head>
<body>
<div class="slip-container">
    <div class="slip-header">
        <img src="img/logo/logo%20text-01.png" width="100" height="50">
        <h3>SAC 10 YEARS ANNIVERSARY</h3>
        <h3>21 กันยายน 2567</h3>
        <h2 id="ar_name" class="large-text"></h2>
        <h2 id="table_number" class="large-text"></h2>
    </div>
    <div class="slip-footer">
        <p>ขอขอบคุณที่เข้าร่วมงานในครั้งนี้</p>
    </div>
</div>

</body>
</html>
