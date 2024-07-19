<?php
include '..\config\connect_db.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $stmt = $conn->prepare("SELECT * FROM v_event_checkin WHERE ar_name LIKE :query OR phone LIKE :query");
    $stmt->execute(['query' => "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        foreach ($results as $row) {
            echo '<div class="row">';
            echo '<div class="form-group">';
            echo '<label for="c-form-name">';
            echo '<span class="label-text">ข้อมูลที่ค้นพบ :</span>';
            echo '</label>';
            echo '<h1 class="card-title">' . "ชื่อผู้เข้าร่วมงาน : " . htmlspecialchars($row['ar_name']) . '</h1>';
            echo '<h1 class="card-title">' . "หมายเลขโทรศัพท์ : " . htmlspecialchars($row['phone']) . '</h1>';
            echo '<h1 class="card-title">' . "หมายเลขโต๊ะ : " . htmlspecialchars($row['table_number']) . '</h1>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="alert alert-warning">No results found</div>';
    }
}