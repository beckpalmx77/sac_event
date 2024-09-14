<?php

// ฟังก์ชันสำหรับส่งข้อความ
function send_Message($access_token,$userId, $messageText) {

    //Test User ID to Send LINE
    //$userId = "U819e0fa74e0a1f4c0df7b46b346da789";

    //global $access_token;
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

/*
$users = [
    'U819e0fa74e0a1f4c0df7b46b346da789' => 'Hello, User 1!',
    'U8c2d4aac5e422daa1c1f739b4885250e' => 'Hello, User Kanoon!',

    // เพิ่มผู้ใช้และข้อความที่ต้องการส่ง
];

foreach ($users as $userId => $message) {
    $result = sendMessage($userId, $message);
    echo "Message sent to $userId: $result\n";
}

*/

