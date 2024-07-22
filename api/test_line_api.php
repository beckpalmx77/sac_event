<?php
// Channel Access Token จาก LINE Developers Console (long-lived)
$access_token = 'lbqZr1z3xLpzKlwoHzYNU3WrC/mQVM70huk1Px/h/0JEbG4wuV45xZEYdDJ8viUAoGIgVi6rV+7NgZxp3nmtCn6mnazWJCbk/I0++o+JRr9nrL8C+o21uoNpm0xqFgtRdukCMxaF7ZuJ3eZvF7/DLQdB04t89/1O/w1cDnyilFU=';

// ฟังก์ชันสำหรับส่งข้อความ
function sendMessage($userId, $messageText) {
    global $access_token;

    $url = 'https://api.line.me/v2/bot/message/push';
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
    ];

    $data = [
        'to' => $userId,
        'messages' => [
            [
                'type' => 'text',
                'text' => $messageText,
            ],
        ],
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // ปิดการตรวจสอบ SSL ชั่วคราว (สำหรับการทดสอบเท่านั้น)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpcode != 200) {
        return "Error: $error\nResponse: $result\nHTTP Code: $httpcode";
    }

    return $result;
}

// ตัวอย่างการส่งข้อความไปยังผู้ใช้หลายคน
$users = [
    'beckpalmx' => 'Hello, User 1!',
    'numberoek' => 'Hello, User 2!',
    // เพิ่มผู้ใช้และข้อความที่ต้องการส่ง
];

foreach ($users as $userId => $message) {
    $result = sendMessage($userId, $message);
    echo "Message sent to $userId: $result\n";
}
?>
