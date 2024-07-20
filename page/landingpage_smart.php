<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>บริษัท สงวนออโต้คาร์ จำกัด</title>
    <link rel="shortcut icon" href="img/logo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flipclock/0.7.8/flipclock.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flipclock/0.7.8/flipclock.css">
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .image-container img {
            display: block;
            margin: 0;
            padding: 0;
            border: none;
        }

    </style>

    <style>
        .clock {
            margin: 0.275em auto;
            width: 320px;
        }
        .message {
            text-align: center;
            font-size: 2em;
            margin-top: 20px;
        }
    </style>

    <style>
        p {
            text-align: center;
            font-size: 40px;
            margin-top: 0px;
        }
    </style>



</head>
<body>
<!-- START OF FLIP EXAMPLE PRESET -->

<img src="img/sac10year_1_1.png" alt="Nature" class="responsive">
<img src="img/sac10year_1_2.png" alt="Nature" class="responsive">

<p id="CountDown"></p>

<p id="message" class="message"></p>

<img src="img/sac10year_2_1.png" alt="Nature" class="responsive">

<div class="image-container">
    <img src="img/sac10year_3_1.png" alt="Nature" class="responsive">
    <img src="img/sac10year_3_2.png" alt="Nature" class="responsive">
    <img src="img/sac10year_3_3.png" alt="Nature" class="responsive">
    <img src="img/sac10year_3_4.png" alt="Nature" class="responsive">
    <img src="img/sac10year_3_5.png" alt="Nature" class="responsive">
</div>

<a href="http://171.100.56.194:8999/sac_event/page/" target="_blank">
    <img src="img/sac10year_456.png" alt="Nature" class="responsive">
</a>


<!--script>
    $(document).ready(function() {
        // Set the date we're counting down to
        let countDownDate = new Date("Sep 21, 2024 17:00:00").getTime();

        // Calculate the time remaining in seconds
        let now = new Date().getTime();
        let distance = countDownDate - now;
        let secondsRemaining = Math.floor(distance / 1000);

        // Initialize the flip clock
        let clock = $('#countdown').FlipClock(secondsRemaining, {
            clockFace: 'DailyCounter',
            countdown: true,
            callbacks: {
                stop: function() {
                    $('#message').html('EXPIRED');
                }
            }
        });
    });
</script-->

<script>
    // Set the date we're counting down to
    let countDownDate = new Date("Sep 21, 2024 17:00:00").getTime();

    // Update the count down every 1 second
    let x = setInterval(function() {

        // Get today's date and time
        let now = new Date().getTime();

        // Find the distance between now and the count down date
        let distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        document.getElementById("CountDown").innerHTML = days + " Days \n\r" + hours + " Hours \n\r"
            + minutes + " Minutes \n\r" + seconds + " Seconds ";

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("CountDown").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>


</body>
</html>

