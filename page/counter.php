<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width">
    <title>Flip.js Flip Clock Example</title>
    <link href="https://www.cssscript.com/wp-includes/css/sticky.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="flip/flip.min.css">

</head>
<body>


<!--style>
    .container { margin: 150px auto; max-width: 960px; }
    .tick {
        font-size:1rem;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    .tick-label {
        font-size:.375em;
        text-align:center;
    }

    .tick-group {
        margin:0 .25em;
        text-align:center;
    }
</style-->

<div class="container">
    <img src="img/sac_event_img_1.png" alt="Nature" class="responsive" usemap="#image-map">
    <div class="tick"
         data-did-init="handleTickInit">
        <div data-repeat="true"
             data-layout="horizontal center fit"
             data-transform="preset(d, h, m, s) -> delay">
            <div class="tick-group">
                <div data-key="value"
                     data-repeat="true"
                     data-transform="pad(00) -> split -> delay">
                    <span data-view="flip"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function handleTickInit(tick) {

        // Uncomment to set labels to different language ( in this case Dutch )
        /*
        let locale = {
            YEAR_PLURAL: 'Jaren',
            YEAR_SINGULAR: 'Jaar',
            MONTH_PLURAL: 'Maanden',
            MONTH_SINGULAR: 'Maand',
            WEEK_PLURAL: 'Weken',
            WEEK_SINGULAR: 'Week',
            DAY_PLURAL: 'Dagen',
            DAY_SINGULAR: 'Dag',
            HOUR_PLURAL: 'Uren',
            HOUR_SINGULAR: 'Uur',
            MINUTE_PLURAL: 'Minuten',
            MINUTE_SINGULAR: 'Minuut',
            SECOND_PLURAL: 'Seconden',
            SECOND_SINGULAR: 'Seconde',
            MILLISECOND_PLURAL: 'Milliseconden',
            MILLISECOND_SINGULAR: 'Milliseconde'
        };

        for (let key in locale) {
            if (!locale.hasOwnProperty(key)) { continue; }
            tick.setConstant(key, locale[key]);
        }
        */

        //let nextYear = (new Date()).getFullYear() + 1;

        Tick.count.down('2024-09-21').onupdate = function(value) {
            tick.value = value;
        };

    }
</script>

<!-- END OF FLIP EXAMPLE PRESET -->

<script src="flip/flip.min.js"></script>


</body>
</html>

