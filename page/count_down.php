<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Countdown Flip Clock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flipclock/0.7.8/flipclock.css">
    <style>
        .clock {
            margin: 2em auto;
            width: 800px;
        }
        .message {
            text-align: center;
            font-size: 2em;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="clock" id="countdown"></div>
<p id="message" class="message"></p>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flipclock/0.7.8/flipclock.min.js"></script>
<script>
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
</script>

</body>
</html>

