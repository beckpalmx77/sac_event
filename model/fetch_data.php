<?php

include('../config/connect_db.php');

$limit = $_POST['length'];
$start = $_POST['start'];
$searchValue = $_POST['search']['value'];
$selectOption = $_POST['selectOption'];
$searchText = $_POST['searchText'];

$sql = "SELECT * FROM evs_customer WHERE 1=1";

$params = [];

if ($selectOption != '') {
    $sql .= " AND option_field = :selectOption";
    $params[':selectOption'] = $selectOption;
}

if ($searchText != '') {
    $sql .= " AND (ar_name LIKE :searchText OR sale_contact_name LIKE :searchText)";
    $params[':searchText'] = "%$searchText%";
}

if (!empty($searchValue)) {
    $sql .= " AND (ar_name LIKE :searchValue OR sale_contact_name LIKE :searchValue)";
    $params[':searchValue'] = "%$searchValue%";
}

$sql .= " LIMIT :start, :limit";
$params[':start'] = $start;
$params[':limit'] = $limit;

$stmt = $conn->prepare($sql);

/*
$txt = $sql ;
$my_file = fopen("cond_text0.txt", "w") or die("Unable to open file!");
fwrite($my_file, $txt);
fclose($my_file);
*/

foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val, PDO::PARAM_STR);
}

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalData = $conn->query("SELECT COUNT(*) FROM employees")->fetchColumn();

$sqlFiltered = "SELECT COUNT(*) FROM employees WHERE 1=1";

if ($selectOption != '') {
    $sqlFiltered .= " AND option_field = :selectOption";
}

if ($searchText != '') {
    $sqlFiltered .= " AND (ar_name LIKE :searchText OR sale_contact_name LIKE :searchText)";
}

if (!empty($searchValue)) {
    $sqlFiltered .= " AND (ar_name LIKE :searchValue OR sale_contact_name LIKE :searchValue)";
}

$stmtFiltered = $conn->prepare($sqlFiltered);

foreach ($params as $key => &$val) {
    if (strpos($key, 'start') !== false || strpos($key, 'limit') !== false) {
        continue;
    }
    $stmtFiltered->bindParam($key, $val, PDO::PARAM_STR);
}

$stmtFiltered->execute();
$totalFiltered = $stmtFiltered->fetchColumn();

$json_data = [
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

/*
$txt = $sqlFiltered ;
$my_file = fopen("cond_text.txt", "w") or die("Unable to open file!");
fwrite($my_file, $txt);
fclose($my_file);

file_put_contents("z_data.json", json_encode($json_data));
*/

echo json_encode($json_data);

