<?php

ini_set('display_errors', 1);
error_reporting(~0);

include("../config/connect_sqlserver.php");
include("../config/connect_db.php");

$sql_sqlsvr = "SELECT * FROM DEPTTAB ";
$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {

    $sql_find = "SELECT * FROM mdepartment WHERE department_id = '" . $result_sqlsvr["DEPT_KEY"] . "'";
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {

        $sql = "UPDATE mdepartment SET department_desc=:department_desc,status=:status
        WHERE department_id = :department_id ";

        echo $sql . "\n\r";

        $query = $conn->prepare($sql);
        $query->bindParam(':department_desc', $result_sqlsvr["DEPT_THAIDESC"], PDO::PARAM_STR);
        $query->bindParam(':status', $result_sqlsvr["DEPT_ENABLED"], PDO::PARAM_STR);
        $query->bindParam(':department_id', $result_sqlsvr["DEPT_KEY"], PDO::PARAM_STR);
        $query->execute();

    } else {

        $sql = "INSERT INTO mdepartment(department_id,department_desc,status)
        VALUES (:department_id,:department_desc,:status)";

        echo $sql . "\n\r";

        $query = $conn->prepare($sql);
        $query->bindParam(':department_id', $result_sqlsvr["DEPT_KEY"], PDO::PARAM_STR);
        $query->bindParam(':department_desc', $result_sqlsvr["DEPT_THAIDESC"], PDO::PARAM_STR);
        $query->bindParam(':status', $result_sqlsvr["DEPT_ENABLED"], PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $conn->lastInsertId();

        if ($lastInsertId) {
            echo "Save OK" . "\n\r" ;
        } else {
            echo "Error";
        }
    }

}



