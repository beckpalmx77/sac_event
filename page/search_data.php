<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajax Search with Bootstrap 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .card-body-highlight {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>


<div class="container-fluid" id="container-wrapper">

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-8">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                </div>
                <div class="card-body">
                    <section class="container-fluid">
                        <div class="medium-text font-weight-bold text-uppercase mb-1 "
                             style="color: #8F35F6;">
                            ลงทะเบียนเข้างาน
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h2>Ajax Search with Bootstrap 5</h2>
    <form id="searchForm">
        <div class="mb-3">
            <input type="text" id="searchText" class="form-control" placeholder="Search...">
        </div>
    </form>
    <div id="result" class="mt-3 row row-cols-1 row-cols-md-3 g-4"></div>
</div>

<script>
    $(document).ready(function() {
        $('#searchText').on('keyup', function() {
            let query = $(this).val();
            if (query.length >= 10) { // ค้นหาหลังจากพิมพ์เกิน 2 ตัวอักษร
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