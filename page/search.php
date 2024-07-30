<?php
include '..\config\connect_db.php';

// ตั้งค่าชื่อไฟล์รูปภาพต้นฉบับและรูปภาพที่จะแสดงผล
$imageFile = 'img_gen/sac_10.png';
$outputFile = '';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $stmt = $conn->prepare("SELECT * FROM v_event_checkin WHERE ar_name LIKE :query OR phone LIKE :query OR cust_name_1 LIKE :query ");
    $stmt->execute(['query' => "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        foreach ($results as $row) {
            echo '<br>';
            $outputFile = 'img_gen/sac_10_' . $row['event_id'] . "-" . $row['id'] . ".png";

            $text_chk = $row['ar_name'];
            // ข้อความที่ต้องการแสดงบนรูปภาพ
            $text = $row['ar_name'] . "\n\r";
            $text .= "โทร : " . $row['phone'] . "\n\r";
            $text .= "โต๊ะหมายเลข : " . $row['table_number'];
            include 'gen_text_img.php';
            echo '<div><img src="' . $outputFile . '" alt="' . basename($outputFile) . '" style="max-width:100%; height:auto;"/></div>';

/*
            echo '<div class="c-form-top">';
            echo '<div class="c-form-top-left">';
            echo '<div class="form-group">';
            echo '<label for="c-form-name">';
            echo '<span class="label-text">ข้อมูลที่ค้นพบ :</span>';
            echo '</label>';
            echo '<h3 class="card-title">' . "<b>" . htmlspecialchars($row['ar_name']) . '</b></h3>';
            echo '<h3 class="card-title">' . "<b>" . htmlspecialchars($row['phone']) . '</b></h3>';
            echo '<h1 class="card-title">' . "หมายเลขโต๊ะ : " . "<b>" . htmlspecialchars($row['table_number']) . '</b></h1>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
*/

        }
    } else {
            echo '<div class="alert alert-warning">ไม่พบข้อมูลตามที่ค้นหา</div>';
    }
}