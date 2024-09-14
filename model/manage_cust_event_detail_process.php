<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');
include('../util/reorder_record.php');

if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT cust.* FROM evs_customer cust                         
            WHERE cust.id = " . $id;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "cust_id" => $result['cust_id'],
            "ar_name" => $result['ar_name'],
            "cust_name_1" => $result['cust_name_1'],
            "phone" => $result['phone'],
            "province_name" => $result['province_name'],
            "sale_contact_name" => $result['sale_contact_name']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["ar_name"] !== '') {

        $cust_id = $_POST["cust_id"];
        $sql_find = "SELECT * FROM evs_customer WHERE cust_id = '" . $cust_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {
    if ($_POST["phone"] !== '' && $_POST["cust_id"] !== '') {
        //$cust_id = $dept_id . "-" . substr($phone, 6) . "-" . sprintf('%04s', LAST_ID($conn, "evs_customer", 'id'));
        $cust_id = $_POST["cust_id"];
        $phone = $_POST["phone"];
        $ar_name = $_POST["ar_name"];
        $dept_id = $_POST["dept_id"];
        $work_time_id = $_POST["work_time_id"];
        $remark = $_POST["remark"];
        $sex = $_POST["sex"];
        $prefix = $_POST["prefix"];
        $province_name = $_POST["province_name"];
        $position = $_POST["position"];
        $start_work_date = $_POST["start_work_date"];

        $sql_find = "SELECT * FROM evs_customer WHERE cust_id = '" . $cust_id . "'";

        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo $dup;
        } else {
            $sql = "INSERT INTO evs_customer (cust_id,phone,ar_name,work_time_id,dept_id,remark,email_address,sex,prefix,province_name,position,start_work_date) 
                    VALUES (:cust_id,:phone,:ar_name,:work_time_id,:dept_id,:remark,:email_address,:sex,:prefix,:province_name,:position,:start_work_date)";

            $query = $conn->prepare($sql);
            $query->bindParam(':cust_id', $cust_id, PDO::PARAM_STR);
            $query->bindParam(':phone', $phone, PDO::PARAM_STR);
            $query->bindParam(':ar_name', $ar_name, PDO::PARAM_STR);
            $query->bindParam(':work_time_id', $work_time_id, PDO::PARAM_STR);
            $query->bindParam(':dept_id', $dept_id, PDO::PARAM_STR);
            $query->bindParam(':remark', $remark, PDO::PARAM_STR);
            $query->bindParam(':email_address', $email, PDO::PARAM_STR);
            $query->bindParam(':sex', $sex, PDO::PARAM_STR);
            $query->bindParam(':prefix', $prefix, PDO::PARAM_STR);
            $query->bindParam(':province_name', $province_name, PDO::PARAM_STR);
            $query->bindParam(':position', $position, PDO::PARAM_STR);
            $query->bindParam(':start_work_date', $start_work_date, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $conn->lastInsertId();
            if ($lastInsertId) {
                $sql_user = "INSERT INTO ims_user (cust_id,user_id,first_name,last_name,password,department_id,account_type,picture,company,email) 
                    VALUES (:cust_id,:user_id,:first_name,:last_name,:password,:dept_id,:account_type,:user_picture,:company,:email)";
                $query_user = $conn->prepare($sql_user);
                $query_user->bindParam(':cust_id', $cust_id, PDO::PARAM_STR);
                $query_user->bindParam(':user_id', $cust_id, PDO::PARAM_STR);
                $query_user->bindParam(':first_name', $phone, PDO::PARAM_STR);
                $query_user->bindParam(':last_name', $ar_name, PDO::PARAM_STR);
                $query_user->bindParam(':password', $user_password, PDO::PARAM_STR);
                $query_user->bindParam(':dept_id', $dept_id, PDO::PARAM_STR);
                $query_user->bindParam(':account_type', $account_type_default, PDO::PARAM_STR);
                $query_user->bindParam(':user_picture', $user_picture, PDO::PARAM_STR);
                $query_user->bindParam(':company', $company, PDO::PARAM_STR);
                $query_user->bindParam(':email', $email, PDO::PARAM_STR);
                $query_user->execute();
                $lastInsertUser = $conn->lastInsertId();
                if ($lastInsertUser) {
                    Reorder_Record($conn, "ims_user");
                    echo $save_success;
                }
            } else {
                echo $error;
            }
        }
    }
}


if ($_POST["action"] === 'UPDATE') {

    if ($_POST["id"] != '') {

        $id = $_POST["id"];
        $cust_id = $_POST["cust_id"];
        $phone = $_POST["phone"];
        $ar_name = $_POST["ar_name"];
        $cust_name_1 = $_POST["cust_name_1"];
        $province_name = $_POST["province_name"];
        $sale_contact_name = $_POST["sale_contact_name"];

        $sql_find = "SELECT * FROM evs_customer WHERE id = '" . $id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {

            $sql_update = "UPDATE evs_customer SET ar_name=:ar_name,phone=:phone,province_name=:province_name,sale_contact_name=:sale_contact_name             
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':ar_name', $ar_name, PDO::PARAM_STR);
            $query->bindParam(':phone', $phone, PDO::PARAM_STR);
            $query->bindParam(':province_name', $province_name, PDO::PARAM_STR);
            $query->bindParam(':sale_contact_name', $sale_contact_name, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo $save_success;
/*
            $txt = $id . " | " . $cust_id . " | " . $week_holiday . " | " . $save_success;
            $my_file = fopen("holiday_a.txt", "w") or die("Unable to open file!");
            fwrite($my_file, $txt);
            fclose($my_file);
*/

        }

    }
}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM evs_customer WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM evs_customer WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_CUSTOMER') {

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
    //if ($_POST["page_manage"]!=="ADMIN") {
    //$searchQuery = " AND cust_id = '" . $_SESSION['cust_id'] . "'";
    //}

    if ($searchValue != '') {
        $searchQuery = " AND (cust_id LIKE :cust_id or ar_name LIKE :ar_name or cust_name_1 LIKE :cust_name_1 or
        phone LIKE :phone or province_name LIKE :province_name or sale_contact_name LIKE :sale_contact_name) ";
        $searchArray = array(
            'cust_id' => "%$searchValue%",
            'ar_name' => "%$searchValue%",
            'phone' => "%$searchValue%",
            'cust_name_1' => "%$searchValue%",
            'province_name' => "%$searchValue%",
            'sale_contact_name' => "%$searchValue%"
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM evs_customer ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM evs_customer WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $sql_getdata = "SELECT * FROM evs_customer WHERE 1 " . $searchQuery . " ORDER BY id  " . " LIMIT :limit,:offset";

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

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {

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
                "sale_contact_name" => $row['sale_contact_name'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "status" => $row['status'] === 'Y' ? "<div class='text-success'>" . $row['status'] . "</div>" : "<div class='text-muted'> " . $row['status'] . "</div>",
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
