<html>
<head>
    <link rel="stylesheet" href="/vendor/jquery-timepicker-1.3.5/jquery.timepicker.css">
    <script src="vendor/jquery-timepicker-1.3.5/jquery.timepicker.js"></script>


    <style>
        body {
            background: #EFEFEF;
            padding: 20px;
        }

        h1 {
            font-weight: bold;
            margin-top: 100px;
        }

        p {
            margin: 10px 0;
        }
    </style>

    <script>
        $(document).ready(function () {
            $('input.timepicker').timepicker({
                timeFormat: 'HH:mm:ss',
// year, month, day and seconds are not important
                minTime: new Date(0, 0, 0, 8, 0, 0),
                maxTime: new Date(0, 0, 0, 15, 0, 0),
// time entries start being generated at 6AM but the plugin
// shows only those within the [minTime, maxTime] interval
                startHour: 6,
// the value of the first item in the dropdown, when the input
// field is empty. This overrides the startHour and startMinute
// options
                startTime: new Date(0, 0, 0, 8, 20, 0),
// items in the dropdown are separated by at interval minutes
                interval: 10
            });
        });
    </script>

</head>

<h1>jQuery TimePicker Demo</h1>

<input class="timepicker" name="timepicker"/>


</html>