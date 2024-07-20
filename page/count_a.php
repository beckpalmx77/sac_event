<html>
<body>
<p class="time">
    <!-- days -->
    <span id='day-ten-digit' class="show">
      0
    </span>
    <span id='day-last-digit' class="show">
      0
    </span>
    <span className="indicate">days</span>

    <!-- hours -->
    <span id='hour-ten-digit' class="show">
      0
    </span>
    <span id='hour-last-digit' class="show">
      0
    </span>
    <span className="indicate">hours</span>

    <!-- min -->
    <span id='min-ten-digit' class="show">
      0
    </span>
    <span id='min-last-digit' class="show">
      0
    </span>
    <span className="indicate">mins</span>

    <!-- sec -->
    <span id='sec-ten-digit' class="show">
      0
    </span>
    <span id='sec-last-digit' class="show">
      0
    </span>
    <span className="indicate">secs</span>
</p>

<script>
    // ประกาศฟังก์ชันที่ใช้ตรวจว่าเหลือเวลาอีกเท่าไหร่
    const getTimeLeft = (now) => {
        // นี่คือเวลาที่เราจะ Countdown
        // เราก็แปลงให้มันเป็น timestamp
        // รูปแบบเวลาที่ใส่ใน new Date() ต้องเป็นแบบนี้ >> เดือน/วันที่/ปี ชั่วโมง:นาที:วินาที
        const DATE_WILL_DIFF = (new Date('1/1/2020 00:00:00')).getTime();

        // ถ้าเวลาตอนนี้มันเลยจากเวลาที่เรากำหนด
        // ให้ทุกตัว (วัน ชั่วโมง นาที และวินาที) มีค่าเท่ากับ 0 ทั้งหมด
        if (now > DATE_WILL_DIFF) {
            return {
                days: 0,
                hours: 0,
                min: 0,
                sec: 0,
            }
        }

        // ถ้ามันยังไม่เลยเวลา ก็ให้หาผลต่าง
        // เราดูตัวนี้ได้เลยว่า เหลือเวลาเท่าไหร่
        let diff = DATE_WILL_DIFF - now;

        // ผลต่างที่ได้จะยังเป็นมิลลิวินาทีอยู่ ซึ่งคงดูไม่รู้เรื่อง
        // ค้องใช้สูตรแปลงให้เป็น วัน ชั่วโมง นาที และวินาทีก่อน ตามข้างล่าง


        // 1 วินาที = 1000 มิลลิวินาที
        let sec = Math.floor(diff / 1000);

        // 1 นาที = 60 วินาที
        let min = Math.floor(sec / 60);

        // 1 ชั่วโมง = 60 นาที
        let hours = Math.floor(min / 60);

        // 1 วัน = 24 ชั่วโมง
        const days = Math.floor(hours / 24);

        // วินาทีที่ได้ จะเป็นจำนวนวินาทีทั้งหมดที่เหลืออยู่
        // เช่น "ถ้าเหลือเวลา 1244308437 มิลลิวินาที" หาร 1000 ไปก็จะได้เท่ากับ "เหลือเวลาอีก 244308 วินาที"
        // เราแบ่งวินาทีนี้ออกเป็น นาที ชั่วโมง และ วัน ในบรรทัดที่ 33-42 แล้ว
        // และถ้าเราอยากรู้ว่า ตอนนี้อยู่ที่วินาทีที่เท่าไหร่ (0-59) ให้ modulo กับ 60
        sec %= 60;

        // จำนวนนาที ปรับให้อยู่ในช่วง 0 - 59
        min %= 60;

        // จำนวนชั่วโมง ปรับให้อยู่ในช่วง 0 - 23
        hours %= 24;

        // ส่งกลับไปเป็น JavaScript Object
        // { days, hours, min, sec }
        // เท่ากับ
        // {
        //  'days': days,
        //  'hours': hours,
        //  'min': min,
        //  'sec': sec
        // }

        return {
            days,
            hours,
            min,
            sec,
        };
    };



    // ประกาศฟังก์ชันที่ใช้ตรวจว่า หมดเวลาหรือยัง

    const isTimeOut = (time) => time.days === 0 && time.hours === 0 && time.min === 0 && time.sec === 0;

    const getLastDigit = (number) => {
        return number % 10;
    };

    const getTenDigit = (number) => {
        return Math.floor((number % 100) / 10);
    };



    // เริ่มจับเวลา! โดยใช้ setInterval
    // ประกาศตัวแปรเก็บการจับเวลา
    let timer;

    // ประกาศฟังก์ชัน countdown
    // ใน JavaScript ประกาศได้ 2 แบบนะ
    // คือแบบด้านล่าง
    // กับแบบใหม่ เรียกว่า Arrow Function >> const [ชื่อฟังก์ชัน] = (parameters) => {...} ตามที่เขียนไว้ข้างบน
    function countdown() {
        // คำสั่งนี้ใช้ดึงเวลาปัจจุบัน
        // new Date() จะได้เวลารูปแบบนี้ >> Tue Dec 17 2019 13:19:58 GMT+0700 (Indochina Time)
        // พ่วง .getTime() มาด้วย เวลาที่มาจาก new Date() จะกลายเป็นตัวเลขยาว ๆ แบบนี้ >> 1576563692198
        // มันคืออะไร? มันคือ timestamp ตัวเลขแสดงเวลาระดับมิลลิวินาที (milisecond) ที่ถูกนับตั้งแต่เที่ยงคืนของเวลา 1 มกราคม ค.ศ.1970
        // มันใช้เปรียบเทียบกับเวลาในทุกที่ทุกเครื่องได้โดยไม่เพี้ยน
        // เราจะใช้เจ้าตัวนี้นี่แหละไปเปรียบเทียบกับเวลาที่เราจะ Countdown
        const now = (new Date()).getTime();

        const timeLeft = getTimeLeft(now);
        // หทดเวลาแล้ว
        if (isTimeOut(timeLeft)) {
            // หยุดการทำงานของ setInterval
            clearInterval(timer);
            // เปลี่ยนใ้ห้เป็นข้อตวามที่ต้องการ
            document.getElementById('show').innerHTML = "HAPPY NEW YEAR";
            return;
        }
        // ถ้ายังไม่หมดเวลา ให้แสดงเวลาที่ได้จากฟังก์ชัน getTimeLeft
        document.getElementById('day-ten-digit').innerHTML = getTenDigit(timeLeft.days);
        document.getElementById('day-last-digit').innerHTML = getLastDigit(timeLeft.days);
        document.getElementById('hour-ten-digit').innerHTML = getTenDigit(timeLeft.hours);
        document.getElementById('hour-last-digit').innerHTML = getLastDigit(timeLeft.hours);
        document.getElementById('min-ten-digit').innerHTML = getTenDigit(timeLeft.min);
        document.getElementById('min-last-digit').innerHTML = getLastDigit(timeLeft.min);
        document.getElementById('sec-ten-digit').innerHTML = getTenDigit(timeLeft.sec);
        document.getElementById('sec-last-digit').innerHTML = getLastDigit(timeLeft.sec);
    }

    // ใช้ setInterval เพื่อให้มันวนลูปนับเวลาถอยหลังไปเรื่อย ๆ
    // ให้ฟังก์ชัน countdown ทำงานทุก ๆ 1000 มิลลิวินาที (ซึ่งก็คือ 1 วินาทีนั่นเอง)
    timer = setInterval(countdown, 1000);
</script>
</body></html>
