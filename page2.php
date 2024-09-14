<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h1>Page 2 - Display Random Value</h1>
<p id="display"></p>

<script>
    function fetchRandomValue() {
        $.ajax({
            url: 'get_page.php',
            type: 'GET',
            success: function(response) {
                $('#display').text('Random Value: ' + response);
            }
        });
    }

    // Poll the server every 2 seconds to get the latest value
    setInterval(fetchRandomValue, 2000);
</script>
</body>
</html>
