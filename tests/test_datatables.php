<?php
include 'config/connect_db.php';

// Fetch request data from DataTables
$draw = $_REQUEST['draw'];
$start = $_REQUEST['start'];
$length = $_REQUEST['length'];
$orderColumnIndex = $_REQUEST['order'][0]['column']; // Column index
$orderColumnDir = $_REQUEST['order'][0]['dir']; // Order direction 'asc' or 'desc'
$searchValue = $_REQUEST['search']['value']; // Search value

// Define columns mapping
$columns = array(
    0 => 'cust_id',
    1 => 'ar_name',
    2 => 'cust_name_1',
    3 => 'phone'
);

// Determine the column to order by
$orderColumn = $columns[$orderColumnIndex];

// Default to descending order if not specified
$orderDirection = strtoupper($orderColumnDir) === 'ASC' ? 'ASC' : 'DESC';

// SQL query for data retrieval with search and order
$sql = "SELECT * FROM evs_customer WHERE 1=1";

// Add search functionality
if (!empty($searchValue)) {
    $sql .= " AND (cust_id LIKE :search OR ar_name LIKE :search OR cust_name_1 LIKE :search OR phone LIKE :search)";
}

// Add ordering
$sql .= " ORDER BY $orderColumn $orderDirection";

// Add limit for pagination
$sql .= " LIMIT :start, :length";

$stmt = $conn->prepare($sql);

// Bind parameters
if (!empty($searchValue)) {
    $stmt->bindValue(':search', '%' . $searchValue . '%', PDO::PARAM_STR);
}
$stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
$stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);

// Execute the statement
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total records
$totalRecords = $conn->query("SELECT COUNT(*) FROM evs_customer")->fetchColumn();

// Total records with filtering
$totalFilteredRecords = $stmt->rowCount();

// Prepare the JSON response
$response = array(
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFilteredRecords),
    "data" => $data
);

// Send the response
echo json_encode($response);

