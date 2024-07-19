<?php
include '..\config\connect_db.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $stmt = $conn->prepare("SELECT * FROM v_event_checkin WHERE ar_name LIKE :query OR phone LIKE :query");
    $stmt->execute(['query' => "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        foreach ($results as $row) {
            echo '<br>';
            echo '<div class="c-form-top">';
            echo '<div class="c-form-top-left">';
            echo '<div class="form-group">';
            echo '<label for="c-form-name">';
            echo '<span class="label-text">ข้อมูลที่ค้นพบ :</span>';
            echo '</label>';
            echo '<h3 class="card-title">' . htmlspecialchars($row['ar_name']) . '</h3>';
            echo '<h3 class="card-title">' . htmlspecialchars($row['phone']) . '</h3>';
            echo '<h3 class="card-title">' . "หมายเลขโต๊ะ : " . htmlspecialchars($row['table_number']) . '</h3>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
            echo '<div class="alert alert-warning">ไม่พบข้อมูลตามที่ค้นหา</div>';
    }
}