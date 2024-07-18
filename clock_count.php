<?php include('includes/Header.php'); ?>
<html>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/clock_assets/flipclock.css"/>
<script type="text/javascript" src="css/clock_assets/flipclock.js"></script>

<style text="text/css">body .flip-clock-wrapper ul li a div div.inn, body .flip-clock-small-wrapper ul li a div div.inn {
        color: #CCCCCC;
        background-color: #333333;
    }

    body .flip-clock-dot, body .flip-clock-small-wrapper .flip-clock-dot {
        background: #323434;
    }

    body .flip-clock-wrapper .flip-clock-meridium a, body .flip-clock-small-wrapper .flip-clock-meridium a {
        color: #323434;
    }
</style>

<script type="text/javascript">
    $(function () {
        FlipClock.Lang.Custom = {days: 'Days', hours: 'Hours', minutes: 'Minutes', seconds: 'Seconds'};
        let opts = {
            clockFace: 'DailyCounter',
            countdown: true,
            language: 'Custom'
        };
        let countdown = 1725962400 - ((new Date().getTime()) / 1000); // from: 09/10/2024 05:00 pm +0700
        countdown = Math.max(1, countdown);
        $('.clock-builder-output').FlipClock(countdown, opts);
    });
</script>

<body>
<div id="content-wrapper" class="d-flex flex-column">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-12">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                </div>
                <div class="card-body">
                    <section class="container-fluid">

                        <div class="col-md-12 col-md-offset-2">
                            <div class="clock-builder-output"></div>
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</body>
</html>
