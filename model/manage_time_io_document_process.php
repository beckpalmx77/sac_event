<?php
session_start();
error_reporting(0);

include('../config/config_rabbit.inc');
include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');
include('../util/GetData.php');
include('../util/send_message.php');

if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT wt.*,lt.leave_type_detail,lt.leave_before,ms.status_doc_desc,em.f_name,em.l_name 
            FROM v_worktime_emp_io wt
            left join mleave_type lt on lt.leave_type_id = wt.leave_type_id
            left join mstatus ms on ms.status_doctype = 'LEAVE' and ms.status_doc_id = wt.status
            left join memployee em on em.emp_id = wt.emp_id  
            WHERE wt.id = " . $id;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "doc_id" => $result['doc_id'],
            "doc_date" => $result['doc_date'],
            "doc_year" => $result['doc_year'],
            "leave_type_id" => $result['leave_type_id'],
            "leave_type_detail" => $result['leave_type_detail'],
            "emp_id" => $result['emp_id'],
            "date_leave_start" => $result['date_leave_start'],
            "date_leave_to" => $result['date_leave_to'],
            "time_leave_start" => $result['time_leave_start'],
            "time_leave_to" => $result['time_leave_to'],
            "f_name" => $result['f_name'],
            "l_name" => $result['l_name'],
            "full_name" => $result['f_name'] . " " .  $result['l_name'],
            "approve_1_id" => $result['approve_1_id'],
            "approve_1_status" => $result['approve_1_status'],
            "approve_2_id" => $result['approve_2_id'],
            "approve_2_status" => $result['approve_2_status'],
            "leave_before" => $result['leave_before'],
            "remark" => $result['remark'],
            "status" => $result['status']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["leave_type_id"] !== '') {

        $doc_id = $_POST["doc_id"];
        $sql_find = "SELECT * FROM v_worktime_emp_io WHERE doc_id = '" . $doc_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {

    if ($_POST["doc_date"] !== '' && $_POST["emp_id"] !== '' && $_POST["leave_type_id"]!== '') {

        $table = "v_worktime_emp_io";
        $dept_id = $_POST["department"];
        $doc_date = $_POST["doc_date"];
        $doc_year = substr($_POST["date_leave_start"], 6);
        $doc_month = substr($_POST["date_leave_start"], 3,2);
        $filed = "id";

        $sql_get_dept = "SELECT mp.dept_ids AS data FROM memployee em LEFT JOIN mdepartment mp ON mp.department_id = em.dept_id WHERE em.emp_id = '" . $_POST["emp_id"] . "'";

        $dept_id_save = GET_VALUE($conn, $sql_get_dept);

        $sql_get_dept_desc = "SELECT mp.department_desc AS data FROM memployee em LEFT JOIN mdepartment mp ON mp.department_id = em.dept_id WHERE em.emp_id = '" . $_POST["emp_id"] . "'";

        $dept_desc = GET_VALUE($conn, $sql_get_dept_desc);

        $emp_full_name = $_POST["full_name"];

        $leave_type_desc = $_POST["leave_type_detail"];

        $condition = " WHERE doc_year = '" . $doc_year . "' AND doc_month = '" . $doc_month . "' AND dept_id = '" . $_SESSION['department_id'] .  "'";

        $last_number = LAST_DOCUMENT_NUMBER($conn,$filed,$table,$condition);

        $doc_id = "WT-" . $_SESSION['department_id'] . "-" . substr($doc_date, 3) . "-" . sprintf('%04s', $last_number);

        //$myfile = fopen("dept-param.txt", "w") or die("Unable to open file!");
        //fwrite($myfile,  $dept_id_save . " | " . $dept_desc . " | " . $emp_full_name . " | " . $leave_type_desc);
        //fclose($myfile);

        $leave_type_id = $_POST["leave_type_id"];
        $emp_id = $_POST["emp_id"];
        $date_leave_start = $_POST["date_leave_start"];
        $time_leave_start = $_POST["time_leave_start"];
        $date_leave_to = $_POST["date_leave_to"];
        $time_leave_to = $_POST["time_leave_to"];
        $remark = $_POST["remark"];
        $status = $_POST["status"];

        $sql_time = "SELECT TIMEDIFF('". $time_leave_to . "','" . $time_leave_start ."') AS total_time ";
        foreach ($conn->query($sql_time) AS $row) {
            $total_time = $row['total_time'];
        }

        $day_max = GET_VALUE($conn, "SELECT day_max AS data FROM mleave_type WHERE leave_type_id ='" . $leave_type_id . "'");

        $cnt_day = "";
        $sql_cnt = "SELECT COUNT(*) AS days FROM dholiday_event WHERE doc_year = '" . $doc_year . "' AND emp_id = '" . $emp_id . "'";
        foreach ($conn->query($sql_cnt) AS $row) {
            $cnt_day = $row['days'];
        }
            $sql_find = "SELECT * FROM v_worktime_emp_io wt WHERE wt.date_leave_start = '" . $date_leave_start . "' AND wt.emp_id = '" . $emp_id . "' ";

            $nRows = $conn->query($sql_find)->fetchColumn();
            if ($nRows > 0) {
                echo $dup;
            } else {
                $sql = "INSERT INTO worktime_emp_io (doc_id,doc_year,doc_month,dept_id,doc_date,leave_type_id,emp_id,date_leave_start,time_leave_start,date_leave_to,time_leave_to,total_time,remark) 
                    VALUES (:doc_id,:doc_year,:doc_month,:dept_id,:doc_date,:leave_type_id,:emp_id,:date_leave_start,:time_leave_start,:date_leave_to,:time_leave_to,:total_time,:remark)";

                //$myfile = fopen("condition-param.txt", "w") or die("Unable to open file!");
                //fwrite($myfile,  $sql);
                //fclose($myfile);

                $query = $conn->prepare($sql);
                $query->bindParam(':doc_id', $doc_id, PDO::PARAM_STR);
                $query->bindParam(':doc_year', $doc_year, PDO::PARAM_STR);
                $query->bindParam(':doc_month', $doc_month, PDO::PARAM_STR);
                $query->bindParam(':dept_id', $_SESSION['department_id'], PDO::PARAM_STR);
                $query->bindParam(':doc_date', $doc_date, PDO::PARAM_STR);
                $query->bindParam(':leave_type_id', $leave_type_id, PDO::PARAM_STR);
                $query->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
                $query->bindParam(':date_leave_start', $date_leave_start, PDO::PARAM_STR);
                $query->bindParam(':time_leave_start', $time_leave_start, PDO::PARAM_STR);
                $query->bindParam(':date_leave_to', $date_leave_to, PDO::PARAM_STR);
                $query->bindParam(':time_leave_to', $time_leave_to, PDO::PARAM_STR);
                $query->bindParam(':total_time', $total_time, PDO::PARAM_STR);
                $query->bindParam(':remark', $remark, PDO::PARAM_STR);

                $query->execute();
                $lAStInsertId = $conn->lAStInsertId();

                if ($lAStInsertId) {

                    $sToken = "gf0Sx2unVFgz7u81vqrU6wcUA2XLLVoPOo2d0Dlvdlr";
                    //$sToken = "zgbi6mXoK6rkJWSeFZm5wPjQfiOniYnV2MOxXeTMlA1";
                    $sMessage = "มีเอกสารการลงเวลาการทำงานกรณีสแกนนิ้วไม่ได้ เลขที่เอกสาร = " . $doc_id . " วันที่เอกสาร = " . $doc_date
                        . "\n\r" . "วันที่ขอลงเวลาการทำงาน : " . $date_leave_start . " เวลา : " . $time_leave_start
                        . "\n\r" . "ผู้ขอ : " . $emp_full_name  . " " .  $dept_desc;

                    echo $sMessage ;
                    sendLineNotify($sMessage,$sToken);
                    echo $save_success;

                } else {
                    echo $error;
                }

            }

    } else {
        echo $error;
    }
}



if ($_POST["action"] === 'UPDATE') {

    if ($_POST["doc_id"] != '') {
        $id = $_POST["id"];
        $doc_id = $_POST["doc_id"];
        $doc_date = $_POST["doc_date"];
        $doc_year = substr($_POST["date_leave_start"],6);
        $dept_id = $_POST["department"];
        $leave_type_id = $_POST["leave_type_id"];
        $emp_id = $_POST["emp_id"];
        $date_leave_start = $_POST["date_leave_start"];
        $time_leave_start = $_POST["time_leave_start"];
        $date_leave_to = $_POST["date_leave_to"];
        $time_leave_to = $_POST["time_leave_to"];
        $remark = $_POST["remark"];
        $status = $_POST["status"];

        $sql_time = "SELECT TIMEDIFF('". $time_leave_to . "','" . $time_leave_start ."') AS total_time ";
        foreach ($conn->query($sql_time) AS $row) {
            $total_time = $row['total_time'];
        }

        $sql_find = "SELECT * FROM v_worktime_emp_io WHERE doc_id = '" . $doc_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {

            if ($_SESSION['approve_permission']==="Y") {
                $sql_update = "UPDATE worktime_emp_io SET status=:status,leave_type_id=:leave_type_id
                ,date_leave_start=:date_leave_start,date_leave_to=:date_leave_to
                ,time_leave_start=:time_leave_start,time_leave_to=:time_leave_to,remark=:remark,doc_year=:doc_year,total_time=:total_time     
                ,emp_id=:emp_id                  
                WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->bindParam(':leave_type_id', $leave_type_id, PDO::PARAM_STR);
                $query->bindParam(':date_leave_start', $date_leave_start, PDO::PARAM_STR);
                $query->bindParam(':date_leave_to', $date_leave_to, PDO::PARAM_STR);
                $query->bindParam(':time_leave_start', $time_leave_start, PDO::PARAM_STR);
                $query->bindParam(':time_leave_to', $time_leave_to, PDO::PARAM_STR);
                $query->bindParam(':remark', $remark, PDO::PARAM_STR);
                $query->bindParam(':doc_year', $doc_year, PDO::PARAM_STR);
                $query->bindParam(':total_time', $total_time, PDO::PARAM_STR);
                $query->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                echo $save_success;
            } else {
                $sql_update = "UPDATE worktime_emp_io SET leave_type_id=:leave_type_id
                ,date_leave_start=:date_leave_start,date_leave_to=:date_leave_to
                ,time_leave_start=:time_leave_start,time_leave_to=:time_leave_to,remark=:remark,doc_year=:doc_year,total_time=:total_time     
                ,emp_id=:emp_id                  
                WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':leave_type_id', $leave_type_id, PDO::PARAM_STR);
                $query->bindParam(':date_leave_start', $date_leave_start, PDO::PARAM_STR);
                $query->bindParam(':date_leave_to', $date_leave_to, PDO::PARAM_STR);
                $query->bindParam(':time_leave_start', $time_leave_start, PDO::PARAM_STR);
                $query->bindParam(':time_leave_to', $time_leave_to, PDO::PARAM_STR);
                $query->bindParam(':remark', $remark, PDO::PARAM_STR);
                $query->bindParam(':doc_year', $doc_year, PDO::PARAM_STR);
                $query->bindParam(':total_time', $total_time, PDO::PARAM_STR);
                $query->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                echo $save_success;
            }
        }

    } else {
        echo $Approve_Success;
    }

}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM worktime_emp_io WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM worktime_emp_io WHERE id = " . $id;
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
    $searchQuery = " ";

    if ($_SESSION['document_dept_cond']!=="A") {
        $searchQuery = " AND wt.dept_id = '" . $_SESSION['department_id'] . "'";
    }

    if ($searchValue != '') {
        $searchQuery = " AND (wt.f_name LIKE :f_name or wt.l_name LIKE :l_name or wt.department_id LIKE :department_id or wt.leave_type_id LIKE :leave_type_id or
        wt.doc_date LIKE :doc_date ) ";
        $searchArray = array(
            'f_name' => "%$searchValue%",
            'l_name' => "%$searchValue%",
            'department_id' => "%$searchValue%",
            'leave_type_id' => "%$searchValue%",
            'doc_date' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM v_worktime_emp_io wt ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM v_worktime_emp_io wt WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $TotalRecordwithFilter = $records['allcount'];



## Fetch records
    $stmt = $conn->prepare("SELECT wt.*,lt.leave_type_detail,ms.status_doc_desc,em.f_name,em.l_name,em.department_id 
            FROM v_worktime_emp_io wt
            left join mleave_type lt on lt.leave_type_id = wt.leave_type_id
            left join mstatus ms on ms.status_doctype = 'LEAVE' and ms.status_doc_id = wt.status
            left join memployee em on em.emp_id = wt.emp_id  
            WHERE 1 " . $searchQuery . " ORDER BY id desc , " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");


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
                "doc_date" => $row['doc_date'],
                "doc_year" => $row['doc_year'],
                "emp_id" => $row['emp_id'],
                "leave_type_id" => $row['leave_type_id'],
                "leave_type_detail" => $row['leave_type_detail'],
                "date_leave_start" => $row['date_leave_start'],
                "date_leave_to" => $row['date_leave_to'],
                "time_leave_start" => $row['time_leave_start'],
                "time_leave_to" => $row['time_leave_to'],
                "dt_leave_start" => $row['date_leave_start'] . " " .  $row['time_leave_start'],
                "dt_leave_to" => $row['date_leave_to'] . " " .  $row['time_leave_to'],
                "department_id" => $row['department_id'],
                "remark" => $row['remark'],
                "full_name" => $row['f_name'] . " " .  $row['l_name'],
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
        "iTotalDisplayRecords" => $TotalRecordwithFilter,
        "aaData" => $data
    );

    echo json_encode($response);

}
