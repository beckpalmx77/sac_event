<?php

include '../config/connect_db.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $stmt = $conn->prepare("SELECT * FROM v_event_checkin WHERE ar_name LIKE :query OR phone LIKE :query");
    $stmt->execute(['query' => "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        foreach ($results as $row) {
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($row['ar_name']) . '</h5>';
            echo '<p class="card-text">โทร: ' . htmlspecialchars($row['phone']) . '</p>';
            echo '<p class="card-text">โต๊ะหมายเลข: ' . htmlspecialchars($row['table_number']) . '</p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="alert alert-warning">ไม่พบข้อมูลตามที่ค้นหา</div>';
    }
}
?>
