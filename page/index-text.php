<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาข้อมูล</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* ปรับแต่ง CSS เพิ่มเติมสำหรับหน้าจอขนาดเล็ก */
        .container {
            max-width: 100%;
            padding: 15px;
        }
        .card {
            margin-bottom: 10px;
        }
        .form-control {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="c-form-top-left">
        <h3>ค้นหาข้อมูล เข้าร่วมงาน</h3>
        <p>กรอก หมายเลขโทรศัพท์ หรือ ชื่อ เพื่อค้นหาข้อมูล</p>
    </div>
    <div class="c-form-top-right">
        <div class="c-form-top-right-icon">
            <i class="fa fa-heart" style="color: #0F9347;"></i>
        </div>
    </div>
    <form id="searchForm" class="mt-3">
        <div class="mb-3">
            <label for="searchText" class="form-label">คำค้นหา:</label>
            <input type="text" class="form-control" id="searchText" name="searchText" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
    </form>
    <hr>
    <div id="result"></div>
</div>

<script>
    $(document).ready(function() {
        $('#searchForm').on('submit', function(e) {
            e.preventDefault(); // ป้องกันไม่ให้ฟอร์มทำการ submit ปกติ
            $('#result').empty(); // ล้างผลลัพธ์ก่อน

            let query = $('#searchText').val();
            if (query.length >= 2) { // ตรวจสอบว่ามีความยาวของคำค้นหาอย่างน้อย 2 ตัวอักษร
                $.ajax({
                    url: 'search_text.php',
                    method: 'POST',
                    data: { query: query },
                    success: function(data) {
                        if (data.trim() === '') {
                            $('#result').html('<div class="alert alert-warning">ไม่พบข้อมูลตามที่ค้นหา</div>');
                        } else {
                            $('#result').html(data);
                        }
                    },
                    error: function() {
                        $('#result').html('<div class="alert alert-danger">เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง</div>');
                    }
                });
            } else {
                $('#result').html('<div class="alert alert-info">กรุณากรอกคำค้นหาอย่างน้อย 2 ตัวอักษร</div>');
            }
        });
    });
</script>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
