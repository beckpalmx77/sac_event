<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');
include('../util/reorder_record.php');
include('../util/thai_date_util.php');
include('../util/send_line_msg.php');
include('../util/GetData.php');

if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM v_event_checkin         
                WHERE v_event_checkin.id = " . $id;

    /*
        $txt = $sql_get;
        $my_file = fopen("search_cond.txt", "w") or die("Unable to open file!");
        fwrite($my_file, $txt);
        fclose($my_file);
    */

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "cust_id" => $result['cust_id'],
            "ar_name" => $result['ar_name'],
            "cust_name_1" => $result['cust_name_1'],
            "cust_name_2" => $result['cust_name_2'],
            "cust_name_3" => $result['cust_name_3'],
            "cust_name_4" => $result['cust_name_4'],
            "cust_name_5" => $result['cust_name_5'],
            "cust_name_6" => $result['cust_name_6'],
            "cust_name_7" => $result['cust_name_7'],
            "cust_name_8" => $result['cust_name_8'],
            "cust_name_9" => $result['cust_name_9'],
            "cust_name_10" => $result['cust_name_10'],
            "cust_name_11" => $result['cust_name_11'],
            "cust_name_12" => $result['cust_name_12'],
            "cust_level" => $result['cust_level'],
            "nickname" => $result['nickname'],
            "group_guest" => $result['group_guest'],
            "phone" => $result['phone'],
            "province_name" => $result['province_name'],
            "register_qty" => $result['register_qty'],
            "attendance_qty" => $result['attendance_qty'],
            "room_reserve_qty" => $result['room_reserve_qty'],
            "check_in_status" => $result['check_in_status'],
            "table_number" => $result['table_number'],
            "sale_contact_name" => $result['sale_contact_name']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action"] === 'UPDATE_DETAIL') {

    if ($_POST["id"] != '') {

        $id = $_POST["id"];
        $cust_name_1 = $_POST["cust_name_1"];
        $cust_name_2 = $_POST["cust_name_2"];
        $cust_name_3 = $_POST["cust_name_3"];
        $cust_name_4 = $_POST["cust_name_4"];
        $cust_name_5 = $_POST["cust_name_5"];
        $cust_name_6 = $_POST["cust_name_6"];
        $cust_name_7 = $_POST['cust_name_7'];
        $cust_name_8 = $_POST['cust_name_8'];
        $cust_name_9 = $_POST['cust_name_9'];
        $cust_name_10 = $_POST['cust_name_10'];
        $phone = $_POST["phone"];
        $register_qty = $_POST["register_qty"];
        $attendance_qty = $_POST["attendance_qty"];
        $table_number = $_POST["table_number"];

        $sql_find = "SELECT * FROM evs_event_checkin WHERE id = '" . $id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            try {
                $sql_update = "UPDATE evs_event_checkin SET cust_name_1=:cust_name_1,cust_name_2=:cust_name_2,cust_name_3=:cust_name_3
            ,cust_name_4=:cust_name_4,cust_name_5=:cust_name_5,cust_name_6=:cust_name_6
            ,cust_name_7=:cust_name_7,cust_name_8=:cust_name_8,cust_name_9=:cust_name_9,cust_name_10=:cust_name_10
            ,phone_number=:phone
            ,register_qty=:register_qty,attendance_qty=:attendance_qty,table_number=:table_number             
            WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':cust_name_1', $cust_name_1, PDO::PARAM_STR);
                $query->bindParam(':cust_name_2', $cust_name_2, PDO::PARAM_STR);
                $query->bindParam(':cust_name_3', $cust_name_3, PDO::PARAM_STR);
                $query->bindParam(':cust_name_4', $cust_name_4, PDO::PARAM_STR);
                $query->bindParam(':cust_name_5', $cust_name_5, PDO::PARAM_STR);
                $query->bindParam(':cust_name_6', $cust_name_6, PDO::PARAM_STR);
                $query->bindParam(':cust_name_7', $cust_name_7, PDO::PARAM_STR);
                $query->bindParam(':cust_name_8', $cust_name_8, PDO::PARAM_STR);
                $query->bindParam(':cust_name_9', $cust_name_9, PDO::PARAM_STR);
                $query->bindParam(':cust_name_10', $cust_name_10, PDO::PARAM_STR);
                $query->bindParam(':phone', $phone, PDO::PARAM_STR);
                $query->bindParam(':register_qty', $register_qty, PDO::PARAM_STR);
                $query->bindParam(':attendance_qty', $attendance_qty, PDO::PARAM_STR);
                $query->bindParam(':table_number', $table_number, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                echo $save_success;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

    }
}

if ($_POST["action"] === 'CONFIRM') {

    if ($_POST["id"] != '') {

        $id = $_POST["id"];
        $ar_name = $_POST["ar_name"];
        $sale_contact_name = $_POST["sale_contact_name"];
        $cust_name_1 = $_POST["cust_name_1"];
        $cust_name_2 = $_POST["cust_name_2"];
        $cust_name_3 = $_POST["cust_name_3"];
        $cust_name_4 = $_POST["cust_name_4"];
        $cust_name_5 = $_POST["cust_name_5"];
        $cust_name_6 = $_POST["cust_name_6"];
        $cust_name_7 = $_POST['cust_name_7'];
        $cust_name_8 = $_POST['cust_name_8'];
        $cust_name_9 = $_POST['cust_name_9'];
        $cust_name_10 = $_POST['cust_name_10'];
        $phone = $_POST["phone"];
        $register_qty = $_POST["register_qty"];
        $attendance_qty = $_POST["attendance_qty"];
        $table_number = $_POST["table_number"];
        $check_in_status = "Y";
        $timestamp = time();
        $current_time = date('Y-m-d H:i:s');
        $check_in_date = ($check_in_status === 'Y') ? thai_date($timestamp) : "-";

        $order_record = 1;

        $sql_count = "SELECT COUNT(*) AS RECORD FROM evs_event_checkin WHERE check_in_status = 'Y'";
        $row = $conn->query($sql_count)->fetch();
        if (empty($row["0"])) {
            $order_record = 1;
        } else {
            $order_record = $row["0"] + 1;
        }

        $sql_find = "SELECT * FROM evs_event_checkin WHERE id = '" . $id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();

        if ($nRows > 0) {
            try {
                $sql_update = "UPDATE evs_event_checkin SET cust_name_1=:cust_name_1,cust_name_2=:cust_name_2,cust_name_3=:cust_name_3
                ,cust_name_4=:cust_name_4,cust_name_5=:cust_name_5,cust_name_6=:cust_name_6
                ,cust_name_7=:cust_name_7,cust_name_8=:cust_name_8,cust_name_9=:cust_name_9,cust_name_10=:cust_name_10
                ,phone_number=:phone
                ,register_qty=:register_qty,attendance_qty=:attendance_qty,table_number=:table_number      
                ,check_in_date=:check_in_date, check_in_status=:check_in_status,update_chk_in_date=:update_chk_in_date,order_record=:order_record        
                WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':cust_name_1', $cust_name_1, PDO::PARAM_STR);
                $query->bindParam(':cust_name_2', $cust_name_2, PDO::PARAM_STR);
                $query->bindParam(':cust_name_3', $cust_name_3, PDO::PARAM_STR);
                $query->bindParam(':cust_name_4', $cust_name_4, PDO::PARAM_STR);
                $query->bindParam(':cust_name_5', $cust_name_5, PDO::PARAM_STR);
                $query->bindParam(':cust_name_6', $cust_name_6, PDO::PARAM_STR);
                $query->bindParam(':cust_name_7', $cust_name_7, PDO::PARAM_STR);
                $query->bindParam(':cust_name_8', $cust_name_8, PDO::PARAM_STR);
                $query->bindParam(':cust_name_9', $cust_name_9, PDO::PARAM_STR);
                $query->bindParam(':cust_name_10', $cust_name_10, PDO::PARAM_STR);
                $query->bindParam(':phone', $phone, PDO::PARAM_STR);
                $query->bindParam(':register_qty', $register_qty, PDO::PARAM_STR);
                $query->bindParam(':attendance_qty', $attendance_qty, PDO::PARAM_STR);
                $query->bindParam(':table_number', $table_number, PDO::PARAM_STR);
                $query->bindParam(':check_in_date', $check_in_date, PDO::PARAM_STR);
                $query->bindParam(':check_in_status', $check_in_status, PDO::PARAM_STR);
                $query->bindParam(':update_chk_in_date', $current_time, PDO::PARAM_STR);
                $query->bindParam(':order_record', $order_record, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();

                $sale_contact_name = trim($sale_contact_name);
                $sql_sale_line = "SELECT esn.sale_line_user_id AS data FROM evs_sale_name esn WHERE TRIM(esn.sale_name_desc) = '" . $sale_contact_name . "'";
                $line_user_id = GET_VALUE($conn, $sql_sale_line);
                $msg = $ar_name . " : " . $cust_name_1 . " : " . $phone . " Check In " . $check_in_date;

                /*
                                $txt = $sql_sale_line . " [ " . $line_user_id . " ] " . $msg;
                                $my_file = fopen("send_line_id.txt", "w") or die("Unable to open file!");
                                fwrite($my_file, $txt);
                                fclose($my_file);
                */

                $access_token = 'Shw8xgMW5E9qSgkqGUykrY+YZLAT+PcaM2pdutHSloNWDPMPqjbfrHUycRoM7txPoGIgVi6rV+7NgZxp3nmtCn6mnazWJCbk/I0++o+JRr/j8HP4qSxCksI1E9LlvVozjmywwOS/gqz8maqOcXrofwdB04t89/1O/w1cDnyilFU=';
                //Remove // for production
                //send_Message($access_token, $line_user_id, $msg);

                echo $save_success;

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}

if ($_POST["action"] === 'GET_CUSTOMER_CHECKIN') {

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

    $search_province = $_POST['search_province'];
    $search_name = $_POST['search_name'];
    $search_contact = $_POST['search_contact'];

    /*
        $txt = $search_province . " | " . $search_name . " | " . $search_contact;
        $my_file = fopen("search_cond.txt", "w") or die("Unable to open file!");
        fwrite($my_file, $txt);
        fclose($my_file);
    */

## Search
    $searchQuery = " ";

    if ($searchValue != '') {
        $searchQuery = " AND (ar_name LIKE :ar_name 
        or cust_name_1 LIKE :cust_name_1 or cust_name_2 LIKE :cust_name_2 
        or cust_name_3 LIKE :cust_name_3 or cust_name_4 LIKE :cust_name_4 
        or cust_name_5 LIKE :cust_name_5 or cust_name_6 LIKE :cust_name_6
        or cust_name_7 LIKE :cust_name_7 or cust_name_8 LIKE :cust_name_8
        or cust_name_9 LIKE :cust_name_9 or cust_name_10 LIKE :cust_name_10    
        or phone LIKE :phone or province_name LIKE :province_name 
        or sale_contact_name LIKE :sale_contact_name or table_number LIKE :table_number or group_guest LIKE :group_guest) ";
        $searchArray = array(
            'ar_name' => "%$searchValue%",
            'phone' => "%$searchValue%",
            'cust_name_1' => "%$searchValue%",
            'cust_name_2' => "%$searchValue%",
            'cust_name_3' => "%$searchValue%",
            'cust_name_4' => "%$searchValue%",
            'cust_name_5' => "%$searchValue%",
            'cust_name_6' => "%$searchValue%",
            'cust_name_7' => "%$searchValue%",
            'cust_name_8' => "%$searchValue%",
            'cust_name_9' => "%$searchValue%",
            'cust_name_10' => "%$searchValue%",
            'province_name' => "%$searchValue%",
            'sale_contact_name' => "%$searchValue%",
            'table_number' => "%$searchValue%",
            'group_guest' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM v_event_checkin ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM v_event_checkin WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $sql_getdata = "SELECT * FROM v_event_checkin WHERE 1 " . $searchQuery . " ORDER BY id  " . " LIMIT :limit,:offset";

    /*
                    $txt = $sql_getdata ;
                    $my_file = fopen("cust_a.txt", "w") or die("Unable to open file!");
                    fwrite($my_file, $txt);
                    fclose($my_file);
    */

    $stmt = $conn->prepare($sql_getdata);

// Bind values
    foreach ($searchArray as $key => $search) {
        $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();

    $row_number = 0;

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {

/*
            if ($row['check_in_status'] === 'Y' || $_SESSION['approve_level'] === '3') {
                $update = "<button type='button' disabled name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Check In</button>";
            } else {
                $update = "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Check In</button>";
            }

            if ($_SESSION['approve_level'] !== '1' && $_SESSION['approve_level'] !== '3' ) {
                $detail = "<button type='button' disabled name='detail' id='" . $row['id'] . "' class='btn btn-secondary btn-xs detail' data-toggle='tooltip' title='Detail'>Detail</button>";
            } else {
                $detail = "<button type='button' name='detail' id='" . $row['id'] . "' class='btn btn-secondary btn-xs detail' data-toggle='tooltip' title='Detail'>Detail</button>";
            }
*/

            $update = "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Check In</button>";
            $detail = "<button type='button' name='detail' id='" . $row['id'] . "' class='btn btn-secondary btn-xs detail' data-toggle='tooltip' title='Detail'>Detail</button>";

            $data[] = array(
                "id" => $row['id'],
                "cust_id" => $row['cust_id'],
                "ar_name" => $row['ar_name'],
                "phone" => $row['phone'],
                "province_name" => $row['province_name'],
                "cust_name_1" => $row['cust_name_1'],
                "cust_name_2" => $row['cust_name_2'],
                "cust_name_3" => $row['cust_name_3'],
                "cust_name_4" => $row['cust_name_4'],
                "cust_name_5" => $row['cust_name_5'],
                "cust_name_6" => $row['cust_name_6'],
                "cust_name_7" => $result['cust_name_7'],
                "cust_name_8" => $result['cust_name_8'],
                "cust_name_9" => $result['cust_name_9'],
                "cust_name_10" => $result['cust_name_10'],
                "cust_name_11" => $result['cust_name_11'],
                "cust_name_12" => $result['cust_name_12'],
                "sale_contact_name" => $row['sale_contact_name'],
                "group_guest" => $row['group_guest'],
                "cust_level" => $row['cust_level'],
                "nickname" => $row['nickname'],
                "table_number" => $row['table_number'],
                "detail" => $detail,
                "update" => $update,
                "check_in_status" => $row['check_in_status'] === 'Y' ? "<div class='text-success'>" . $row['check_in_status'] . "</div>" : "<div class='text-danger'> " . $row['check_in_status'] . "</div>",
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "cust_id" => $row['cust_id'],
                "ar_name" => $row['ar_name'],
                "select" => "<button type='button' name='select' id='" . $row['department_id'] . "@" . $row['dept_id'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
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

