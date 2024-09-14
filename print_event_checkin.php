<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
        }

        @media print {
            body {
                width: 4in;
                height: 6in;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .container {
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                border: 1px solid black; /* กรอบสำหรับการพิมพ์ */
                box-sizing: border-box;
                padding: 10px;
            }
            #printForm, .btn, h2 {
                display: none;
            }
            #printArea {
                display: block;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">ข้อมูลสำหรับพิมพ์</h2>
    <form id="printForm">
        <div class="mb-3">
            <label for="name" class="form-label">ชื่อ</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">หมายเลขโทรศัพท์</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="tableNumber" class="form-label">หมายเลขโต๊ะ</label>
            <input type="text" class="form-control" id="tableNumber" name="tableNumber" required>
        </div>
        <button type="button" id="printButton" class="btn btn-primary">พิมพ์ข้อมูล</button>
    </form>
    <div id="printArea" class="d-none mt-5">
        <h4>ข้อมูลที่พิมพ์</h4>
        <p id="printName"></p>
        <p id="printPhone"></p>
        <p id="printTableNumber"></p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#printButton').on('click', function() {
            let formData = $('#printForm').serialize();

            $.ajax({
                type: 'POST',
                url: 'export_process/print_event_chk_in.php',
                data: formData,
                success: function(response) {
                    let data = JSON.parse(response);
                    $('#printName').text('ชื่อ: ' + data.name);
                    $('#printPhone').text('หมายเลขโทรศัพท์: ' + data.phone);
                    $('#printTableNumber').text('หมายเลขโต๊ะ: ' + data.tableNumber);

                    $('#printArea').removeClass('d-none');
                    window.print();  // สั่งพิมพ์หน้าเว็บ
                }
            });
        });
    });
</script>

</body>
</html>
