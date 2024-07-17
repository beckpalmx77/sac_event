<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');
include('../util/GetData.php');
include('../util/send_message.php');

if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT dl.*,lt.leave_type_detail,ms.status_doc_desc FROM vdholiday_event dl
            left join mleave_type lt on lt.leave_type_id = dl.leave_type_id
            left join mstatus ms on ms.status_doctype = 'LEAVE' and ms.status_doc_id = dl.status  
            WHERE dl.id = " . $id;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "doc_id" => $result['doc_id'],
            "doc_year" => $result['doc_year'],
            "doc_date" => $result['doc_date'],
            "leave_type_id" => $result['leave_type_id'],
            "leave_type_detail" => $result['leave_type_detail'],
            "emp_id" => $result['emp_id'],
            "full_name" => $result['f_name'] . " " . $result['l_name'],
            "date_leave_start" => $result['date_leave_start'],
            "date_leave_to" => $result['date_leave_to'],
            "time_leave_start" => $result['time_leave_start'],
            "time_leave_to" => $result['time_leave_to'],
            "approve_1_id" => $result['approve_1_id'],
            "approve_1_status" => $result['approve_1_status'],
            "approve_2_id" => $result['approve_2_id'],
            "approve_2_status" => $result['approve_2_status'],
            "remark" => $result['remark'],
            "status" => $result['status']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["leave_type_id"] !== '') {

        $doc_id = $_POST["doc_id"];
        $sql_find = "SELECT * FROM dholiday_event WHERE doc_id = '" . $doc_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {
    if ($_POST["date_leave_start"] !== '' && $_POST["emp_id"] !== '') {
        $dept_id = $_SESSION['dept_id'];
        $department_id = $_SESSION['department_id'];
        $doc_date = $_POST["doc_date"];
        $doc_year = substr($_POST["date_leave_start"], 6);

        $leave_type_id = $_POST["leave_type_id"];
        $emp_id = $_POST["emp_id"];
        $full_name = $_POST["full_name"];
        $date_leave_start = $_POST["date_leave_start"];
        $time_leave_start = $_POST["time_leave_start"];
        $date_leave_to = $_POST["date_leave_start"];
        $time_leave_to = $_POST["time_leave_to"];
        $remark = $_POST["remark"];

        $currentDate = date('d-m-Y');

        $sql_get_dept = "SELECT mp.dept_ids AS data FROM memployee em LEFT JOIN mdepartment mp ON mp.department_id = em.dept_id WHERE em.emp_id = '" . $_POST["emp_id"] . "'";
        $dept_id_save = GET_VALUE($conn, $sql_get_dept);

        $sql_get_dept_desc = "SELECT mp.department_desc AS data FROM memployee em LEFT JOIN mdepartment mp ON mp.department_id = em.dept_id WHERE em.emp_id = '" . $_POST["emp_id"] . "'";
        $dept_desc = GET_VALUE($conn, $sql_get_dept_desc);

        $doc_id = "H-" . $_SESSION['department_id'] . "-" . substr($doc_date, 6) . "-" . sprintf('%04s', LAST_ID($conn, "dholiday_event", 'id'));

        $day_max = GET_VALUE($conn, "select day_max as data from mleave_type where leave_type_id ='H2' ");

        $cnt_day = "";
        $sql_cnt = "SELECT COUNT(*) as days FROM dholiday_event WHERE doc_year = '" . $doc_year . "' AND emp_id = '" . $emp_id . "'";
        foreach ($conn->query($sql_cnt) as $row) {
            $cnt_day = $row['days'];
        }

        if ($cnt_day >= $day_max) {
            echo $Error_Over;
        } else {

            $sql_find = "SELECT * FROM dholiday_event WHERE date_leave_start = '" . $_POST["date_leave_start"] . "' AND emp_id = '" . $emp_id . "'";
            $nRows = $conn->query($sql_find)->fetchColumn();
            if ($nRows > 0) {
                echo $dup;
            } else {
                $sql = "INSERT INTO dholiday_event (doc_id,doc_year,doc_date,leave_type_id,emp_id,date_leave_start,time_leave_start,date_leave_to,time_leave_to,remark,dept_id) 
                    VALUES (:doc_id,:doc_year,:doc_date,:leave_type_id,:emp_id,:date_leave_start,:time_leave_start,:date_leave_to,:time_leave_to,:remark,:dept_id)";
                $query = $conn->prepare($sql);
                $query->bindParam(':doc_id', $doc_id, PDO::PARAM_STR);
                $query->bindParam(':doc_year', $doc_year, PDO::PARAM_STR);
                $query->bindParam(':doc_date', $currentDate, PDO::PARAM_STR);
                $query->bindParam(':leave_type_id', $leave_type_id, PDO::PARAM_STR);
                $query->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
                $query->bindParam(':date_leave_start', $date_leave_start, PDO::PARAM_STR);
                $query->bindParam(':time_leave_start', $time_leave_start, PDO::PARAM_STR);
                $query->bindParam(':date_leave_to', $date_leave_to, PDO::PARAM_STR);
                $query->bindParam(':time_leave_to', $time_leave_to, PDO::PARAM_STR);
                $query->bindParam(':remark', $remark, PDO::PARAM_STR);
                $query->bindParam(':dept_id', $_SESSION['department_id'], PDO::PARAM_STR);
                $query->execute();
                $lastInsertId = $conn->lastInsertId();
                if ($lastInsertId) {

                    $sToken = "gf0Sx2unVFgz7u81vqrU6wcUA2XLLVoPOo2d0Dlvdlr";
                    //$sToken = "zgbi6mXoK6rkJWSeFZm5wPjQfiOniYnV2MOxXeTMlA1";
                    $sMessage = "มีเอกสารการขอใช้วันหยุดนักขัตฤกษ์-ประจำปี "
                        . "\n\r" . "เลขที่เอกสาร = " . $doc_id
                        . "\n\r" . "วันที่เอกสาร = " . $currentDate
                        . "\n\r" . "วันที่ขอใช้ : " . $date_leave_start
                        . "\n\r" . "ผู้ขอ : " . $full_name  . " " .  $dept_desc;

                    echo $sMessage ;
                    sendLineNotify($sMessage,$sToken);
                    echo $save_success;

                } else {
                    echo $error;
                }
            }
        }
    }
}


if ($_POST["action"] === 'UPDATE') {

    if ($_POST["doc_id"] != '') {

        $id = $_POST["id"];
        $doc_id = $_POST["doc_id"];
        $doc_date = $_POST["doc_date"];
        $dept_id = $_POST["department"];
        $leave_type_id = $_POST["leave_type_id"];
        $emp_id = $_POST["emp_id"];
        $date_leave_start = $_POST["date_leave_start"];
        $time_leave_start = $_POST["time_leave_start"];
        $date_leave_to = $_POST["date_leave_to"];
        $time_leave_to = $_POST["time_leave_to"];
        $remark = $_POST["remark"];
        $status = $_POST["status"];

        $sql_find = "SELECT * FROM dholiday_event WHERE doc_id = '" . $doc_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {

            //if ($_POST["page_manage"] === "ADMIN") {
            if ($_SESSION['document_dept_cond']=="A") {
                $sql_update = "UPDATE dholiday_event SET status=:status
                               WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                echo $save_success;
            } else {
                $sql_update = "UPDATE dholiday_event SET leave_type_id=:leave_type_id
                ,date_leave_start=:date_leave_start,date_leave_to=:date_leave_to
                ,time_leave_start=:time_leave_start,time_leave_to=:time_leave_to,remark=:remark        
                WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':leave_type_id', $leave_type_id, PDO::PARAM_STR);
                $query->bindParam(':date_leave_start', $date_leave_start, PDO::PARAM_STR);
                $query->bindParam(':date_leave_to', $date_leave_to, PDO::PARAM_STR);
                $query->bindParam(':time_leave_start', $time_leave_start, PDO::PARAM_STR);
                $query->bindParam(':time_leave_to', $time_leave_to, PDO::PARAM_STR);
                $query->bindParam(':remark', $remark, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                echo $save_success;
            }
        }

    }
}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM dholiday_event WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM dholiday_event WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_LEAVE_DOCUMENT') {

    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    //$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $columnSortOrder = 'desc'; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $searchArray = array();

## Search
    if ($_SESSION['document_dept_cond']!=="A") {
        $searchQuery = " AND dept_id = '" . $_SESSION['department_id'] . "' ";
    }

/*
    $txt = $searchQuery ;
    $my_file = fopen("leave_1.txt", "w") or die("Unable to open file!");
    fwrite($my_file, $txt);
    fclose($my_file);
*/

    if ($searchValue != '') {
        $searchQuery = " AND (f_name LIKE :f_name or l_name LIKE :l_name or department_id LIKE :department_id or leave_type_id LIKE :leave_type_id or
        doc_date LIKE :doc_date ) ";
        $searchArray = array(
            'f_name' => "%$searchValue%",
            'l_name' => "%$searchValue%",
            'department_id' => "%$searchValue%",
            'leave_type_id' => "%$searchValue%",
            'doc_date' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vdholiday_event ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vdholiday_event WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $orderbyext = "";

    if ($columnSortOrder != '') {
        $orderbyext = ",year desc , month desc , day desc ";
    } else {
        $orderbyext = " order by year desc , month desc , day desc   ";
    }

    $sql_load = "SELECT * FROM vdholiday_event
            WHERE 1 " . $searchQuery
        . " ORDER BY id desc , " . $columnName . " " . $columnSortOrder . $orderbyext . " LIMIT :limit,:offset";

    $stmt = $conn->prepare($sql_load);

/*
            $txt = $sql_load ;
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
                "doc_id" => $row['doc_id'],
                "doc_year" => $row['doc_year'],
                "doc_date" => $row['doc_date'],
                "emp_id" => $row['emp_id'],
                "full_name" => $row['f_name'] . " " . $row['l_name']  ,
                "department_id" => $row['department_id'],
                "leave_type_id" => $row['leave_type_id'],
                "leave_type_detail" => $row['leave_type_detail'],
                "date_leave_start" => $row['date_leave_start'],
                "date_leave_to" => $row['date_leave_to'],
                "time_leave_start" => $row['time_leave_start'],
                "time_leave_to" => $row['time_leave_to'],
                "dt_leave_start" => $row['date_leave_start'] . "  [" . $row['time_leave_start'] . "-" . $row['time_leave_to'] . "] ",
                "t_leave_start" => $row['time_leave_start'] . "-" . $row['time_leave_to'],
                "remark" => $row['remark'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "approve" => "<button type='button' name='approve' id='" . $row['id'] . "' class='btn btn-success btn-xs approve' data-toggle='tooltip' title='Approve'>Approve</button>",
                "status" => $row['status'] === 'A' ? "<div class='text-success'>" . $row['status_doc_desc'] . "</div>" : "<div class='text-muted'> " . $row['status_doc_desc'] . "</div>",
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "leave_type_id" => $row['leave_type_id'],
                "leave_type_detail" => $row['leave_type_detail'],
                "select" => "<button type='button' name='select' id='" . $row['leave_type_id'] . "@" . $row['leave_type_detail'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
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
