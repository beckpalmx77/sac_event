<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Slider</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div id="cardSlider" class="carousel slide" data-ride="false">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="card-deck">
                <div class="card">
                    <img src="image1.jpg" class="card-img-top" alt="Image 1">
                    <div class="card-body">
                        <h5 class="card-title">Card title 1</h5>
                        <p class="card-text">Card description 1</p>
                    </div>
                </div>
                <div class="card">
                    <img src="image2.jpg" class="card-img-top" alt="Image 2">
                    <div class="card-body">
                        <h5 class="card-title">Card title 2</h5>
                        <p class="card-text">Card description 2</p>
                    </div>
                </div>
                <div class="card">
                    <img src="image3.jpg" class="card-img-top" alt="Image 3">
                    <div class="card-body">
                        <h5 class="card-title">Card title 3</h5>
                        <p class="card-text">Card description 3</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- เพิ่ม carousel-item เพิ่มเติมที่นี่ -->
    </div>
    <a class="carousel-control-prev" href="#cardSlider" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#cardSlider" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Custom JS to Initialize the Carousel -->
<script>
    $('#cardSlider').carousel({
        interval: false,  // ปิดการเลื่อนอัตโนมัติ
        wrap: true        // เปิดให้เลื่อนได้รอบๆ
    });
</script>

</body>
</html>
