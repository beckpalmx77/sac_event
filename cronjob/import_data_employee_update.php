<?php

ini_set('display_errors', 1);
error_reporting(~0);

include("../config/connect_sqlserver.php");
include("../config/connect_db.php");

$sql_sqlsvr = "SELECT EMP_KEY,EMP_INTL,EMPFILE.EMP_NAME,EMPFILE.EMP_SURNME,EMPFILE.EMP_GENDER,EMPFILE.EMP_EMAIL,EMPFILE.EMP_TEL
,PERSONALINFO.PRS_SC_D,PAYROLLINFO.PRI_SALARY ,PERSONALINFO.PRS_DEPT
,PERSONALINFO.PRS_JBT,DEPTTAB.DEPT_THAIDESC,JOBTITLE.JBT_THAIDESC
,PAYROLLINFO.PRI_STATUS,EMPFILE.EMP_BIRTH,PERSONALINFO.PRS_NO
FROM EMPFILE 
LEFT JOIN PAYROLLINFO ON PAYROLLINFO.PRI_EMP = EMPFILE.EMP_KEY
LEFT JOIN PERSONALINFO ON PERSONALINFO.PRS_EMP = EMPFILE.EMP_KEY
LEFT JOIN DEPTTAB ON DEPTTAB.DEPT_KEY = PERSONALINFO.PRS_DEPT
LEFT JOIN JOBTITLE ON JOBTITLE.JBT_KEY = PERSONALINFO.PRS_JBT 
ORDER BY PERSONALINFO.PRS_DEPT DESC  ";


//$myfile = fopen("qry_file1.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $sql_sqlsvr);
//fclose($myfile);

$email_address ="@sac.com";

$work_time_id = "S001";

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {

    $sex = $result_sqlsvr["EMP_GENDER"]=="1"?"M":"F";
    $status = $result_sqlsvr["PRI_STATUS"]=="1"?"Y":"N";

    $birth_str = $result_sqlsvr["EMP_BIRTH"]==""?"0000-00-00":$result_sqlsvr["EMP_BIRTH"];
    $birth = substr($birth_str,8,2) . "-" . substr($birth_str,5,2) . "-" . substr($birth_str,0,4) ;

    $start_work_date_str = $result_sqlsvr["PRS_SC_D"]==""?"0000-00-00":$result_sqlsvr["PRS_SC_D"];
    $emp_tel = $result_sqlsvr["EMP_TEL"] ;

    $start_work_date_str = $result_sqlsvr["PRS_SC_D"]==""?"0000-00-00":$result_sqlsvr["PRS_SC_D"];

    $sql_find = "SELECT * FROM memployee WHERE emp_id = '" . $result_sqlsvr["PRS_NO"] . "'";
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {

        echo "UPDATE Employee : " . $result_sqlsvr["PRS_NO"] . "|" . $birth . " | " . $result_sqlsvr["EMP_NAME"] . " | " . $result_sqlsvr["EMP_SURNME"] . $result_sqlsvr["DEPT_THAIDESC"] . "\n\r";

        $sql = "UPDATE memployee SET position_id=:position_id,position=:position,dept_id=:dept_id,department_id=:department_id,
        status=:status,work_time_id=:work_time_id,birthday=:birthday,start_work_date=:start_work_date,phone=:emp_tel
        WHERE emp_id = :emp_id ";

        $query = $conn->prepare($sql);
        $query->bindParam(':position_id', $result_sqlsvr["PRS_JBT"], PDO::PARAM_STR);
        $query->bindParam(':position', $result_sqlsvr["JBT_THAIDESC"], PDO::PARAM_STR);
        $query->bindParam(':dept_id', $result_sqlsvr["PRS_DEPT"], PDO::PARAM_STR);
        $query->bindParam(':department_id', $result_sqlsvr["DEPT_THAIDESC"], PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':work_time_id', $work_time_id, PDO::PARAM_STR);
        $query->bindParam(':birthday', $birth, PDO::PARAM_STR);
        $query->bindParam(':start_work_date', $start_work_date, PDO::PARAM_STR);
        $query->bindParam(':emp_tel', $emp_tel, PDO::PARAM_STR);
        $query->bindParam(':emp_id', $result_sqlsvr["PRS_NO"], PDO::PARAM_STR);
        $query->execute();

    } else {
        echo "Not Found : " . $result_sqlsvr["PRS_NO"] . "|" . $result_sqlsvr["EMP_NAME"] . "|" . $result_sqlsvr["EMP_SURNME"] . "\n\r";
    }

}



