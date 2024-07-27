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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Slip</title>
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
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .slip-footer {
            border-top: 1px dashed #000;
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
                document.getElementById('shop-name').textContent = queryString.shop || '-';
                document.getElementById('shop-address').textContent = queryString.address || '-';
                document.getElementById('shop-phone').textContent = queryString.phone || '-';
                document.getElementById('shop-date').textContent = queryString.date || '<?php echo date('Y-m-d H:i:s'); ?>';
            }
        });
    </script>
</head>
<body>
<div class="slip-container">
    <div class="slip-header">
        <h2>สงวนออโต้คาร์</h2>
        <h2>10 YEARS ANNIVERSARY</h2>
        <h3 id="shop-name"></h3>
        <p id="shop-address">ที่อยู่ร้าน</p>
        <p id="shop-phone">โทรศัพท์: 123-456-7890</p>
        <p id="shop-date">วันที่: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
    <div class="slip-body">
        <?php foreach ($items as $item): ?>
            <div class="item-row">
                <span><?php echo $item['description']; ?> x<?php echo $item['quantity']; ?></span>
                <span><?php echo number_format($item['price'], 2); ?> ฿</span>
            </div>
            <?php
            $total += $item['price'] * $item['quantity'];
        endforeach;
        ?>
    </div>
    <div class="total-row">
        <span>รวมทั้งหมด</span>
        <span><?php echo number_format($total, 2); ?> ฿</span>
    </div>
    <div class="slip-footer">
        <p>ขอบคุณที่ใช้บริการ</p>
        <p>กรุณาตรวจสอบสินค้าและเงินทอน</p>
    </div>
</div>

</body>
</html>

<!-- http://localhost:8888/sac_event/text.php?shop=MyShop&address=MyAddress&phone=9876543210&date=2024-07-26 -->

