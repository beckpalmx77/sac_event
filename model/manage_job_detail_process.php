<?php
session_start();
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');
include('../util/reorder_record.php');


if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];
    $effect_month = $_POST["effect_month"];
    $table_name = $_POST["table_name"];

    $return_arr = array();

    $sql_get = "SELECT * FROM " . $table_name . " WHERE id = " . $id;
    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "job_date" => $result['job_date'],
            "total_job_emp" => $result['total_job_emp'],
            "total_tires" => $result['total_tires'],
            "total_grade_point" => $result['total_grade_point'],
            "total_percent_payment" => $result['total_percent_payment'],
            "total_money" => $result['total_money']);
    }

    echo json_encode($return_arr);

}


if ($_POST["action_detail-bak"] === 'UPDATE') {

    if ($_POST["detail_id"] !== '') {

        $table_name = "job_payment_daily_total";
        $id = $_POST["detail_id"];
        $total_tires = $_POST["total_tires"];

        $sql_update = "UPDATE " . $table_name
            . " SET total_tires=:total_tires"
            . " WHERE id = :id ";
        $query = $conn->prepare($sql_update);
        $query->bindParam(':total_tires', $total_tires, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        if ($query->execute()) {
            echo $save_success;
        } else {
            echo $error;
        }
    }
}

if ($_POST["action_detail"] === 'UPDATE') {
    if ($_POST["detail_id"] != '') {

        $table_name_detail = "job_payment_daily_total";
        $table_name = "job_payment_monthly_total";

        $id = $_POST["detail_id"];
        $total_tires = $_POST["total_tires"];

        $effect_month = substr($_POST["job_date"],3,2);
        $effect_year = substr($_POST["job_date"],6,4);
/*
        $myfile = fopen("job-getdata.txt", "w") or die("Unable to open file!");
        fwrite($myfile, "Month = " . $effect_month . " Year = " . $effect_year);
        fclose($myfile);
*/

        $sql_find = "SELECT * FROM job_payment_daily_total WHERE id = '" . $id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            $sql_update = "UPDATE job_payment_daily_total SET total_tires=:total_tires         
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':total_tires', $total_tires, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();

            $sql_get_sum = "SELECT sum(total_tires) AS sum_total_tires FROM job_payment_daily_total WHERE effect_month = '" . $effect_month . "' AND effect_year = '" . $effect_year .  "'";
            $statement = $conn->query($sql_get_sum);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $result) {
                if ($result['sum_total_tires']>0) {
                    $sum_total_tires = $result['sum_total_tires'];
                } else {
                    $sum_total_tires = 0;
                }
            }

            /*
                        $myfile = fopen("job-getdata-2.txt", "w") or die("Unable to open file!");
                        fwrite($myfile, $sum_total_tires . "\n\r" . $sql_update . "\n\r" . " Month = " . $effect_month . " Year = " . $effect_year);
                        fclose($myfile);
            */

            /* $sql_update_month = "UPDATE job_payment_month_total SET total_tires=:sum_total_tires
            WHERE effect_month = :effect_month AND effect_year = :effect_year"; */

            $sql_update_month = "UPDATE job_payment_month_total SET total_tires= '" . $sum_total_tires . "' "
            . "WHERE effect_month = '" . $effect_month . "' AND effect_year = '" . $effect_year. "'";

/*
            $myfile = fopen("job-getdata-2.txt", "w") or die("Unable to open file!");
            fwrite($myfile, $sum_total_tires . "\n\r" . $sql_update_month);
            fclose($myfile);
*/

            $query_month = $conn->prepare($sql_update_month );
            $query_month->execute();

            echo $save_success;

        }
    }
}


if ($_POST["action"] === 'GET_JOB_DETAIL') {

    ## Read value
    $table_name = $_POST['table_name'];
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
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM " . $table_name
        . " WHERE effect_month = '" . $_POST["effect_month"] . "' AND effect_year = '" . $_POST["effect_year"] . "'");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM " . $table_name
        . " WHERE effect_month = '" . $_POST["effect_month"] . "' AND effect_year = '" . $_POST["effect_year"] . "'");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

    $query_str = "SELECT * FROM " . $table_name
        . " WHERE effect_month = '" . $_POST["effect_month"] . "' AND effect_year = '" . $_POST["effect_year"] . "'"
        . " ORDER BY id ";

    $stmt = $conn->prepare($query_str);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();
    $line = 0;

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {
            $line++;
            $data[] = array(
                "id" => $row['id'],
                "line_no" => $line,
                "job_date" => $row['job_date'],
                "effect_month" => $row['effect_month'],
                "effect_year" => $row['effect_year'],
                "total_job_emp" => $row['total_job_emp'],
                "total_tires" => number_format($row['total_tires'], 2),
                "total_grade_point" => number_format($row['total_grade_point'], 2),
                "total_percent_payment" => number_format($row['total_percent_payment'], 2),
                "total_money" => number_format($row['total_money'], 2),
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
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

