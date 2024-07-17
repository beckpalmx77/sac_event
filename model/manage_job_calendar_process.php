<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');
include('../util/reorder_record.php');

if ($_POST["action"] === 'GET_JOB_DATA') {

    $job_date = $_POST["job_date"];

    $return_arr = array();

    $sql_get = "SELECT * FROM job_payment_daily_total  jd
            WHERE jd.job_date = " . $job_date;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "job_date" => $result['job_date'],
            "effect_month" => $result['effect_month'],
            "effect_year" => $result['effect_year'],
            "total_job_emp" => $result['total_job_emp'],
            "total_tires" => $result['total_tires'],
            "total_grade_point" => $result['total_grade_point'],
            "total_percent_payment" => $result['total_percent_payment'],
            "total_money" => $result['total_money']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action"] === 'GET_JOB_TRANS_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM v_job_transaction  vjtrans
            WHERE vjtrans.id = " . $id;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "job_date" => $result['job_date'],
            "emp_id" => $result['emp_id'],
            "f_name" => $result['f_name'],
            "effect_month" => $result['effect_month'],
            "effect_year" => $result['effect_year'],
            "grade_point" => $result['grade_point'],
            "total_grade_point" => $result['total_grade_point'],
            "total_percent_payment" => $result['total_percent_payment'],
            "total_money" => $result['total_money'],
            "status" => $result['status']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action_detail"] === 'UPDATE') {

    $id = $_POST["detail_id"];
    $job_date_trans = $_POST["job_date_trans"];
    $effect_month = $_POST["effect_month"];
    $effect_year = $_POST["effect_year"];

    $grade_point = strtoupper($_POST["grade_point"]);
    $grade_point = $grade_point !== "" ? $grade_point:"-";
    $sql_update = "UPDATE job_transaction SET grade_point=:grade_point WHERE id = :id";
    $query = $conn->prepare($sql_update);
    $query->bindParam(':grade_point', $grade_point, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();

    echo $save_success;

    include 'calculate_job_payment_process.php';

}

if ($_POST["action"] === 'GET_JOB_DETAIL') {

    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value
    $searchArray = array();

    $job_date = $_POST['job_date'];

## Search
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " AND (grade_point LIKE :grade_point or
        f_name LIKE :f_name ) ";
        $searchArray = array(
            'grade_point' => "%$searchValue%",
            'f_name' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $sql_get_all = "SELECT COUNT(*) AS allcount FROM v_job_transaction WHERE job_date = '" . $job_date . "'";
    $stmt = $conn->prepare($sql_get_all);
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

/*
    $myfile = fopen("job-getdata.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_get_all . " Record = " . $totalRecords);
    fclose($myfile);
*/


## Total number of records with filtering
    $sql_get_filter = "SELECT COUNT(*) AS allcount FROM v_job_transaction WHERE job_date = '" . $job_date . "' " . $searchQuery ;
    $stmt = $conn->prepare($sql_get_filter);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

/*
    $myfile = fopen("job-getdata_2.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_get_filter . " Filter Record = " . $totalRecordwithFilter);
    fclose($myfile);
*/

## Fetch records
    $sql_get_load = "SELECT * FROM v_job_transaction WHERE job_date = '" . $job_date . "' " . $searchQuery
        . " ORDER BY id " . " LIMIT :limit,:offset";

    $stmt = $conn->prepare($sql_get_load);

/*
    $myfile = fopen("job-getdata_3.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_get_load . " Row Record = " . $row . " Row Record Per Page = " . $rowperpage);
    fclose($myfile);
*/

// Bind values
    foreach ($searchArray as $key => $search) {
        $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();

    $line_no = 0;

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {
            $line_no++;
            $data[] = array(
                "id" => $row['id'],
                "line_no" => $line_no,
                "job_date" => $row['job_date'],
                "emp_id" => $row['emp_id'],
                "f_name" => $row['f_name'],
                "grade_point" => ($row['grade_point']!==null && $row['grade_point']!=='') ? $row['grade_point'] : "-" ,
                "total_grade_point" => $row['total_grade_point'],
                "total_percent_payment" => $row['total_percent_payment'],
                "total_money" => $row['total_money'],
                "effect_month" => $row['effect_month'],
                "effect_year" => $row['effect_year'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>"
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "grade_point" => $row['grade_point'],
                "total_grade_point" => $row['total_grade_point'],
                "select" => "<button type='button' name='select' id='" . $row['grade_point'] . "@" . $row['total_grade_point'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
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

