<?php
// Access Token ที่ได้รับจาก LINE Developers Console
$accessToken = 'Shw8xgMW5E9qSgkqGUykrY+YZLAT+PcaM2pdutHSloNWDPMPqjbfrHUycRoM7txPoGIgVi6rV+7NgZxp3nmtCn6mnazWJCbk/I0++o+JRr/j8HP4qSxCksI1E9LlvVozjmywwOS/gqz8maqOcXrofwdB04t89/1O/w1cDnyilFU=';

// Line User ID ที่คุณต้องการดึงข้อมูล
$userId = 'U819e0fa74e0a1f4c0df7b46b346da789';

// URL ของ API เพื่อดึงข้อมูลผู้ใช้
$url = 'https://api.line.me/v2/bot/profile/' . $userId;

// การตั้งค่า cURL
$headers = array(
    'Authorization: Bearer ' . $accessToken
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // เพิ่มการตรวจสอบ HTTP status code
curl_close($ch);

// แปลงผลลัพธ์จาก JSON เป็น Array
$userProfile = json_decode($result, true);

// แสดงผลการตอบสนองจาก API
echo 'Response: ' . $result . '<br>';
echo 'HTTP Status Code: ' . $httpcode . '<br>';

// แสดงชื่อผู้ใช้
if (isset($userProfile['displayName'])) {
    echo 'User Name: ' . $userProfile['displayName'];
} else {
    echo 'Cannot get user profile.';
}
?>
