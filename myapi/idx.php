<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Search</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Search Customer Data</h1>
    <form id="searchForm">
        <div class="form-group">
            <label for="ar_name">Name</label>
            <input type="text" class="form-control" id="ar_name" name="ar_name">
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <h2 class="mt-5">Results</h2>
    <ul id="results" class="list-group"></ul>
</div>

<script>
    $(document).ready(function() {
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            const ar_name = $('#ar_name').val();
            const phone = $('#phone').val();
            $.ajax({
                url: 'http://171.100.56.194:8999/sac_event/myapi/api_cust.php', // เปลี่ยน URL ให้ตรงกับที่ตั้งของ API
                method: 'GET',
                data: { ar_name: ar_name, phone: phone },
                success: function(data) {
                    $('#results').empty();
                    if (data.length > 0) {
                        data.forEach(function(v_event_checkin) {
                            $('#results').append('<li class="list-group-item">' + v_event_checkin.ar_name + ' - ' + v_event_checkin.phone + ' หมายเลขโต๊ะ : ' + v_event_checkin.table_number + '</li>');
                        });
                    } else {
                        $('#results').append('<li class="list-group-item text-warning">No results found.</li>');
                    }
                },
                error: function(xhr) {
                    $('#results').empty();
                    const response = JSON.parse(xhr.responseText);
                    $('#results').append('<li class="list-group-item text-danger">Error: ' + response.error + '</li>');
                }
            });
        });
    });
</script>
</body>
</html>
