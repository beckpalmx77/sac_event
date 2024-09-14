<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randomization Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h1>Page 1 - Randomization</h1>
<button id="randomizeBtn">Randomize</button>
<p id="result"></p>

<script>
    $(document).ready(function() {
        $('#randomizeBtn').click(function() {
            const randomValue = Math.floor(Math.random() * 100) + 1;

            // Send the random value to the server
            $.ajax({
                url: 'store_page.php',
                type: 'POST',
                data: { value: randomValue },
                success: function(response) {
                    $('#result').text('Random Value: ' + randomValue);
                }
            });
        });
    });
</script>
</body>
</html>
