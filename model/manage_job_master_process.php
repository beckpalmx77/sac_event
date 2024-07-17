<?php
session_start();
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');

if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();
    $sql_get = "SELECT * FROM v_job_payment_month_total 
    WHERE id = " . $id;
    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "effect_month" => $result['effect_month'],
            "month_name" => $result['month_name'],
            "total_money" => $result['total_money'],
            "effect_year" => $result['effect_year'],
            "total_tires" => $result['total_tires'],
            "status" => $result['status']);
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'UPDATE') {

    if ($_POST["id"] != '') {

        $id = $_POST["id"];
        $total_tires = $_POST["total_tires"];
        $total_money = $_POST["total_money"];
        $status = $_POST["status"];
        $update_date = date('Y-m-d H:i:s');
        $sql_find = "SELECT * FROM job_payment_month_total WHERE id = " . $id ;
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            $sql_update = "UPDATE job_payment_month_total SET total_money=:total_money            
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            //$query->bindParam(':total_tires', $total_tires, PDO::PARAM_STR);
            $query->bindParam(':total_money', $total_money, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            if ($query->execute()) {
                echo $save_success;
            } else {
                echo $error;
            }
        }

    }
}


if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM job_payment_month_total WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM job_payment_month_total WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_JOB_MASTER') {

    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $searchArray = array();

## Search
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " AND (effect_month LIKE :effect_month or
        effect_year LIKE :effect_year ) ";
        $searchArray = array(
            'effect_month' => "%$searchValue%",
            'effect_year' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM job_payment_month_total ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM job_payment_month_total WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $query_str = "SELECT * FROM job_payment_month_total WHERE 1 " . $searchQuery
        . " ORDER BY id DESC " . " LIMIT :limit,:offset";

    $stmt = $conn->prepare("SELECT * FROM job_payment_month_total WHERE 1 " . $searchQuery
        . " ORDER BY id DESC " . " LIMIT :limit,:offset");

// Bind values
    foreach ($searchArray as $key => $search) {
        $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {
            $data[] = array(
                "id" => $row['id'],
                "effect_month" => $row['effect_month'],
                "effect_year" => $row['effect_year'],
                "total_tires" => $row['total_tires'],
                "total_money" => $row['total_money'],
                "status" => $row['status'] === 'Active' ? "<div class='text-success'>" . $row['status'] . "</div>" : "<div class='text-muted'> " . $row['status'] . "</div>",
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "update_detail" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update_detail' data-toggle='tooltip' title='Update Detail'>Update Detail</button>",
                "delete" => "<button type='button' name='delete' id='" . $row['id'] . "' class='btn btn-danger btn-xs delete' data-toggle='tooltip' title='Delete'>Delete</button>"
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "effect_month" => $row['effect_month'],
                "effect_year" => $row['effect_year'],
                "select" => "<button type='button' name='select' id='" . $row['effect_month'] . "@" . $row['effect_year'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
</button>",
            );
        }

    }

## Response Return Value
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    echo json_encode($response);

}
