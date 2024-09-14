<?php
// Database connection details
$mysql_host = "171.100.56.194";
$mysql_port = "3307";
$mysql_db_name = "sac_event";
$mysql_user = "myadmin";
$mysql_pass = "myadmin";

try
{
    $conn = new PDO("mysql:host=".$mysql_host.";dbname=".$mysql_db_name.";port=" .$mysql_port,$mysql_user, $mysql_pass
        ,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo "Error: " . $e->getMessage();
    exit("Error: " . $e->getMessage());
}

// Your existing script
$accessToken = 'Shw8xgMW5E9qSgkqGUykrY+YZLAT+PcaM2pdutHSloNWDPMPqjbfrHUycRoM7txPoGIgVi6rV+7NgZxp3nmtCn6mnazWJCbk/I0++o+JRr/j8HP4qSxCksI1E9LlvVozjmywwOS/gqz8maqOcXrofwdB04t89/1O/w1cDnyilFU=';

$content = file_put_contents('log_api.json', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

$arrayJson = json_decode($content, true);
$arrayHeader = array();
$arrayHeader[] = "Content-Type: application/json";
$arrayHeader[] = "Authorization: Bearer {$accessToken}";

// รับข้อความจากผู้ใช้
$message = $arrayJson['events'][0]['message']['text'];

// รับ id ของผู้ใช้
$userId = $arrayJson['events'][0]['source']['userId'];

// ดึงข้อมูลโปรไฟล์ของผู้ใช้จาก LINE API
$profileUrl = "https://api.line.me/v2/bot/profile/{$userId}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $profileUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$profile = json_decode($response, true);
$displayName = isset($profile['displayName']) ? $profile['displayName'] : null;

// เก็บ userId และ displayName ลงใน MySQL database
try {
    $stmt = $conn->prepare("INSERT INTO evs_line_user (user_id, user_name) VALUES (:user_id, :user_name)");
    $stmt->execute(['user_id' => $userId, 'user_name' => $displayName]);
} catch (PDOException $e) {
    file_put_contents('cron_log.txt', 'Database error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
}

// ตัวอย่าง Message Type "Text + Sticker"
if ($message == "สวัสดี") {
    $arrayPostData['to'] = $userId;
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "สวัสดีจ้าาา";
    $arrayPostData['messages'][1]['type'] = "sticker";
    $arrayPostData['messages'][1]['packageId'] = "2";
    $arrayPostData['messages'][1]['stickerId'] = "34";
    pushMsg($arrayHeader, $arrayPostData);
}

function pushMsg($arrayHeader, $arrayPostData) {
    $strUrl = "https://api.line.me/v2/bot/message/push";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
}

exit;
?>