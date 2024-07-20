<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title>บริษัท สงวนออโต้คาร์ จำกัด</title>
    <link rel="shortcut icon" href="img/logo.png">

</head>
<body>
<!-- START OF FLIP EXAMPLE PRESET -->

<style>
    .responsive {
        max-width: 100%;
        height: auto;
    }
</style>

<style>
    .tick {
        padding-bottom: 1em;
        font-size: 1rem;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans,
        Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
    }

    .tick-label {
        font-size: 0.375em;
        text-align: center;
    }

    .tick-group {
        margin: 0 0.25em;
        text-align: center;
    }
</style>

<style>
    .image-container {
        display: flex;
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

<img src="img/sac10year_1_1.png" alt="Nature" class="responsive">
<img src="img/sac10year_1_2.png" alt="Nature" class="responsive">
<div class="tick" data-did-init="handleTickInit">
    <div
            data-repeat="true"
            data-layout="horizontal center fit"
            data-transform="preset(d, h, m, s) -> delay">
        <div class="tick-group">
            <div
                    data-key="value"
                    data-repeat="true"
                    data-transform="pad(00) -> split -> delay">
                <span data-view="flip"></span>
            </div>
            <span data-key="label" data-view="text" class="tick-label"></span>
        </div>
    </div>
</div>

<img src="img/sac10year_2_1.png" alt="Nature" class="responsive">

<div class="image-container">
    <img src="img/sac10year_3_1.png" alt="" class="responsive">
    <img src="img/sac10year_3_2.png" alt="" class="responsive">
    <img src="img/sac10year_3_3.png" alt="" class="responsive">
    <img src="img/sac10year_3_4.png" alt="" class="responsive">
    <img src="img/sac10year_3_5.png" alt="" class="responsive">
</div>

<a href="http://171.100.56.194:8999/sac_event/page/" target="_blank"><img src="img/sac10year_456.png" alt="Nature" class="responsive"></a>

<div class="clock-builder-output"></div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="clock_assets/flipclock.js"></script>
<link type="text/css" rel="stylesheet" href="clock_assets/flipclock.css" />
<style text="text/css">body .flip-clock-wrapper ul li a div div.inn, body .flip-clock-small-wrapper ul li a div div.inn { color: #CCCCCC; background-color: #333333; } body .flip-clock-dot, body .flip-clock-small-wrapper .flip-clock-dot { background: #323434; } body .flip-clock-wrapper .flip-clock-meridium a, body .flip-clock-small-wrapper .flip-clock-meridium a { color: #323434; }</style>
<script type="text/javascript">
    $(function(){
        FlipClock.Lang.Custom = { days:'Days', hours:'Hours', minutes:'Minutes', seconds:'Seconds' };
        let opts = {
            clockFace: 'DailyCounter',
            countdown: true,
            language: 'Custom'
        };
        let countdown = 1721471100 - ((new Date().getTime())/1000); // from: 07/20/2024 05:25 pm +0700
        countdown = Math.max(1, countdown);
        $('.clock-builder-output').FlipClock(countdown, opts);
    });
</script>

</body>
</html>
