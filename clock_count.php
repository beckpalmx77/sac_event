<html>
<div class="clock-builder-output"></div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/clock_assets/flipclock.css" />
<script type="text/javascript" src="css/clock_assets/flipclock.js"></script>
<style text="text/css">body .flip-clock-wrapper ul li a div div.inn, body .flip-clock-small-wrapper ul li a div div.inn { color: #CCCCCC; background-color: #333333; } body .flip-clock-dot, body .flip-clock-small-wrapper .flip-clock-dot { background: #323434; } body .flip-clock-wrapper .flip-clock-meridium a, body .flip-clock-small-wrapper .flip-clock-meridium a { color: #323434; }</style>
<script type="text/javascript">
    $(function(){
        FlipClock.Lang.Custom = { days:'Days', hours:'Hours', minutes:'Minutes', seconds:'Seconds' };
        let opts = {
            clockFace: 'DailyCounter',
            countdown: true,
            language: 'Custom'
        };
        let countdown = 1725962400 - ((new Date().getTime())/1000); // from: 09/10/2024 05:00 pm +0700
        countdown = Math.max(1, countdown);
        $('.clock-builder-output').FlipClock(countdown, opts);
    });
</script>
</html>
