<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');
include('../util/GetData.php');

if ($_POST["action"] === 'GET_DATA') {

    $emp_id = $_POST["emp_id"];

    $return_arr = array();

    $sql_get = "SELECT em.*,md.department_desc,mt.work_time_detail FROM memployee em
            left join mdepartment md on md.department_id = em.department_id
            left join mwork_time mt on mt.work_time_id = em.work_time_id  
            WHERE em.emp_id = " . $emp_id;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "emp_id" => $result['emp_id'],
            "f_name" => $result['f_name'],
            "l_name" => $result['l_name'],
            "sex" => $result['sex'],
            "start_work_date" => $result['start_work_date'],
            "department_id" => $result['department_id'],
            "department_desc" => $result['department_desc'],
            "work_time_id" => $result['work_time_id'],
            "work_time_detail" => $result['work_time_detail'],
            "prefix" => $result['prefix'],
            "nick_name" => $result['nick_name'],
            "remark" => $result['remark'],
            "position" => $result['position'],
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

            if ($_POST["page_manage"] === "ADMIN") {
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

if ($_POST["action"] === 'GET_HOLIDAY_DOCUMENT') {

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

    $columnName = " doc_year ";

## Search
    $searchQuery = " ";
    if ($_POST["page_manage"] === "ADMIN") {
        $searchQuery = " AND emp_id = '" . $_POST['emp_id'] . "'";
    }

    if ($searchValue != '') {
        $searchQuery = " AND (doc_year LIKE :doc_year or
        doc_date LIKE :doc_date ) ";
        $searchArray = array(
            'leave_type_id' => "%$searchValue%",
            'doc_date' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM dholiday_event ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM dholiday_event WHERE 1 " . $searchQuery);
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
        //. " ORDER BY " . $columnName . " " . $columnSortOrder . $orderbyext . " LIMIT :limit,:offset";
        . " ORDER BY id desc , " . $columnName . " " . $columnSortOrder . $orderbyext;

    $stmt = $conn->prepare($sql_load);


    //$txt = $sql_load;
    //$my_file = fopen("holiday_a.txt", "w") or die("Unable to open file!");
    //fwrite($my_file, $txt);
    //fclose($my_file);


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
