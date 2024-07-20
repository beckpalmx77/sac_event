<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>บริษัท สงวนออโต้คาร์ จำกัด</title>
    <link rel="shortcut icon" href="img/logo.png">
    <link rel="stylesheet" href="flip/flip.min.css" />
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
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
        @media (max-width: 600px) {
            .tick {
                font-size: 0.75rem;
            }
            .tick-label {
                font-size: 0.3em;
            }
            .image-container img {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<!-- START OF FLIP EXAMPLE PRESET -->

<img src="img/sac10year_1_1.png" alt="Nature" class="responsive">
<img src="img/sac10year_1_2.png" alt="Nature" class="responsive">

<div class="tick" data-did-init="handleTickInit">
    <div data-repeat="true" data-layout="horizontal center fit" data-transform="preset(d, h, m, s) -> delay">
        <div class="tick-group">
            <div data-key="value" data-repeat="true" data-transform="pad(00) -> split -> delay">
                <span data-view="flip"></span>
            </div>
            <span data-key="label" data-view="text" class="tick-label"></span>
        </div>
    </div>
</div>

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

<script>
    function handleTickInit(tick) {
        let endDate = new Date('2024-09-21T17:00:00').getTime();

        Tick.count.down(endDate).onupdate = function (value) {
            tick.value = value;
        };
    }
</script>

<!-- END OF FLIP EXAMPLE PRESET -->

<script src="flip/flip.min.js"></script>
</body>
</html>
