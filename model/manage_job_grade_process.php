<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');


if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM job_grade "
        . " WHERE job_grade.id = " . $id;


    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "grade_id" => $result['grade_id'],
            "detail" => $result['detail'],
            "percent" => $result['percent']);
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'SEARCH_DATA') {

    $grade_id = $_POST["grade_id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM job_grade "
        . " WHERE job_grade.grade_id = '" . $grade_id . "'";
/*
    $myfile = fopen("myqeury_2.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_get);
    fclose($myfile);
*/

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "grade_id" => $result['grade_id'],
            "detail" => $result['detail'],
            "percent" => $result['percent']);
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["grade_id"] !== '') {

        $grade_id = $_POST["grade_id"];
        $sql_find = "SELECT * FROM job_grade WHERE grade_id = '" . $grade_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {
    if ($_POST["detail"] !== '') {
        //$grade_id = "D" . sprintf('%03s', LAST_ID($conn, "job_grade", 'id'));
        $grade_id = $_POST["grade_id"];
        $detail = $_POST["detail"];
        $percent = $_POST["percent"];
        $sql_find = "SELECT * FROM job_grade WHERE grade_id = '" . $grade_id . "'";

        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo $dup;
        } else {
            $sql = "INSERT INTO job_grade(grade_id,detail,percent) 
                    VALUES (:grade_id,:detail,:percent)";
            $query = $conn->prepare($sql);
            $query->bindParam(':grade_id', $grade_id, PDO::PARAM_STR);
            $query->bindParam(':detail', $detail, PDO::PARAM_STR);
            $query->bindParam(':percent', $percent, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $conn->lastInsertId();

            if ($lastInsertId) {
                echo $save_success;
            } else {
                echo $error;
            }
        }
    }
}

if ($_POST["action"] === 'UPDATE') {
    if ($_POST["detail"] != '') {
        $id = $_POST["id"];
        $grade_id = $_POST["grade_id"];
        $detail = $_POST["detail"];
        $percent = $_POST["percent"];
        $sql_find = "SELECT * FROM job_grade WHERE id = '" . $id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            $sql_update = "UPDATE job_grade SET grade_id=:grade_id,detail=:detail
            ,percent=:percent            
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':grade_id', $grade_id, PDO::PARAM_STR);
            $query->bindParam(':detail', $detail, PDO::PARAM_STR);
            $query->bindParam(':percent', $percent, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo $save_success;
        }
    }
}

if ($_POST["action"] === 'DELETE') {
    $id = $_POST["id"];
    $sql_find = "SELECT * FROM job_grade WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM job_grade WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_JOB_GRADE') {
    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $searchArray = array();

    $searchQuery = " ";


    if ($searchValue != '') {
        $searchQuery = " AND (grade_id LIKE :grade_id or
        detail LIKE :detail ) ";
        $searchArray = array(
            'grade_id' => "%$searchValue%",
            'detail' => "%$searchValue%",
        );
    }

## Total number of records without filtering

    $sql_cond = "SELECT COUNT(*) AS allcount FROM job_grade WHERE 1 ";

    $stmt = $conn->prepare($sql_cond);
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM job_grade WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $stmt = $conn->prepare("SELECT * FROM job_grade WHERE 1 " . $searchQuery
        . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

/*
        $txt = $_POST["action"] . " | "  . $_POST["sub_action"] . " | " . $_POST["action_for"] . " | " . $columnName . " | " . $columnSortOrder ;
        $my_file = fopen("leave_a.txt", "w") or die("Unable to open file!");
        fwrite($my_file, $txt);
        fclose($my_file);
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

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {
            $data[] = array(
                "id" => $row['id'],
                "grade_id" => $row['grade_id'],
                "detail" => $row['detail'],
                "percent" => $row['percent'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "delete" => "<button type='button' name='delete' id='" . $row['id'] . "' class='btn btn-danger btn-xs delete' data-toggle='tooltip' title='Delete'>Delete</button>"
            );
        } else {

            $data[] = array(
                "id" => $row['id'],
                "grade_id" => $row['grade_id'],
                "detail" => $row['detail'],
                "select" => "<button type='button' name='select' id='" . $row['grade_id'] . "@" . $row['detail'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
</button>",
            );
/*
            $txt = $txt. ' ' . $row['grade_id'] . " | " .$row['detail'] ;
            $my_file = fopen("leave_select.txt", "w") or die("Unable to open file!");
            fwrite($my_file, $txt);
            fclose($my_file);
*/

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
