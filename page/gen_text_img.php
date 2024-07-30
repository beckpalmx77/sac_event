<?php

// กำหนดฟอนต์ที่ใช้
$fontPath = __DIR__ . '/font/Prompt-ExtraBold.ttf'; // ตั้งค่า path ของฟอนต์ TTF

// ตรวจสอบว่ามีรูปภาพและฟอนต์ที่กำหนดอยู่หรือไม่
if (!file_exists($imageFile) || !file_exists($fontPath)) {
    die('Image file or font file not found.');
}

// สร้างรูปภาพจากไฟล์ที่ระบุ
$image = imagecreatefrompng($imageFile);
if (!$image) {
    die('Failed to load image.');
}

// กำหนดสีของข้อความ (RGB)
$textColor = imagecolorallocate($image, 255, 255, 255); // สีขาว


if (strlen($text)>=115) {
    $fontPath = __DIR__ . '/font/Prompt-SemiBold.ttf'; // ตั้งค่า path ของฟอนต์ TTF
    $fontSize = 10;
} else {
    $fontSize = 14;
}
// มุมของข้อความ
$angle = 0;

// ขนาดของรูปภาพ
$imageWidth = imagesx($image);
$imageHeight = imagesy($image);

// คำนวณขนาดของข้อความ
$textBox = imagettfbbox($fontSize, $angle, $fontPath, $text);

// คำนวณตำแหน่งกึ่งกลางของข้อความ
$textWidth = abs($textBox[4] - $textBox[0]);
$textHeight = abs($textBox[5] - $textBox[1]);

// ตำแหน่ง (x, y) ของข้อความกึ่งกลางภาพ
$x = intval(($imageWidth - $textWidth) / 2);
//$y = intval(($imageHeight + $textHeight) / 2);


// ตำแหน่งของข้อความบนรูปภาพ
//$x = 10; // ระยะห่างจากขอบซ้าย
$y = 265; // ระยะห่างจากขอบบน



// วาดข้อความลงบนรูปภาพ
imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $fontPath, $text);

// บันทึกรูปภาพใหม่ที่มีข้อความ
imagepng($image, $outputFile);

// ปล่อยหน่วยความจำที่ใช้ในการจัดการรูปภาพ
imagedestroy($image);

// แสดงข้อความเสร็จสิ้น
//echo "Image with text has been created: <a href=\"$outputFile\">$outputFile</a>";

