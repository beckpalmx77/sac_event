<?php
include('../config/connect_db.php');

if ($_POST["action"] === 'SEARCH_DATA') {

$emp_id = $_POST["emp_id"];

$return_arr = array();

$sql_get = "SELECT * FROM memployee "
. " WHERE memployee.emp_id = '" . $emp_id . "'";

$statement = $conn->query($sql_get);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {
$return_arr[] = array("id" => $result['id'],
"emp_id" => $result['emp_id'],
"f_name" => $result['f_name'],
"leave_before" => $result['leave_before'],
"day_max" => $result['day_max'],
"work_age_allow" => $result['work_age_allow'],
"day_flag" => $result['day_flag'],
"remark" => $result['remark'],
"status" => $result['status']);
}

echo json_encode($return_arr);

}