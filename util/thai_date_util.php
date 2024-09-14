<?php
function thai_date($timestamp) {
    $thai_day_arr = array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
    $thai_month_arr = array(
        "1"=>"มกราคม",
        "2"=>"กุมภาพันธ์",
        "3"=>"มีนาคม",
        "4"=>"เมษายน",
        "5"=>"พฤษภาคม",
        "6"=>"มิถุนายน",
        "7"=>"กรกฎาคม",
        "8"=>"สิงหาคม",
        "9"=>"กันยายน",
        "10"=>"ตุลาคม",
        "11"=>"พฤศจิกายน",
        "12"=>"ธันวาคม"
    );

    $day = date("w", $timestamp); // Day of the week in numbers
    $date = date("j", $timestamp); // Day of the month without leading zeros
    $month = date("n", $timestamp); // Month without leading zeros
    $year = date("Y", $timestamp) + 543; // Year in Buddhist calendar
    $time = date("H:i", $timestamp); // Time in 24-hour format

    return "วัน".$thai_day_arr[$day]."ที่ ".$date." ".$thai_month_arr[$month]." ".$year." เวลา ".$time." น.";
}

