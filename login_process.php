<?php
session_start();
error_reporting(0);
include('config/connect_db.php');
include('config/lang.php');


if ($_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}


$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$remember = $_POST['remember'];

$sql = "SELECT iu.*,pm.dashboard_page as dashboard_page  
        FROM ims_user iu
        left join ims_permission pm on pm.permission_id = iu.account_type           
        WHERE iu.user_id=:username ";
/*
$txt =  $sql;
$my_file = fopen("login_a.txt", "w") or die("Unable to open file!");
fwrite($my_file, $txt);
fclose($my_file);
*/

$query = $conn->prepare($sql);
$query->bindParam(':username', $username, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if ($query->rowCount() == 1) {
    foreach ($results as $result) {
        if (password_verify($_POST['password'], $result->password)) {
            $_SESSION['alogin'] = $result->user_id;
            $_SESSION['login_id'] = $result->id;
            $_SESSION['username'] = $result->email;
            $_SESSION['emp_id'] = $result->emp_id;
            $_SESSION['first_name'] = $result->first_name;
            $_SESSION['last_name'] = $result->last_name;
            $_SESSION['sex'] = $result->sex;
            $_SESSION['email'] = $result->email;
            $_SESSION['account_type'] = $result->account_type;
            $_SESSION['user_picture'] = $result->picture;
            $_SESSION['dept_id'] = $result->dept_id;
            $_SESSION['department_id'] = $result->department_id;
            $_SESSION['lang'] = $result->lang;
            $_SESSION['permission_price'] = $result->permission_price;
            $_SESSION['approve_level'] = $result->approve_level;
            $_SESSION['dashboard_page'] = $result->dashboard_page;
            $_SESSION['system_name'] = $system_name;

            if ($remember == "on") { // ถ้าติ๊กถูก Login ตลอดไป ให้ทำการสร้าง cookie
                setcookie("username", $_POST["username"], time() + (86400 * 10000), "/");
                setcookie("password", $_POST["password"], time() + (86400 * 10000), "/");
                setcookie("remember_chk", "check", time() + (86400 * 10000), "/");
            } else {
                /*
                setcookie("username", "");
                setcookie("password", "");
                setcookie("remember_chk", "");*/

                setcookie("username", $_POST["username"], time() + (86400 * 10000), "/");
                setcookie("password", $_POST["password"], time() + (86400 * 10000), "/");
                setcookie("remember_chk", "check", time() + (86400 * 10000), "/");
            }
            //echo $result->dashboard_page . ".php";
            echo $result->dashboard_page;

        } else {
            echo 0;
        }
    }
}