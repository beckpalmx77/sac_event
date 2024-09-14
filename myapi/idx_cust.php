<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta ar_name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Search</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Search Customers</h1>
    <form id="searchForm">
        <div class="form-group">
            <label for="ar_name">ar_name</label>
            <input type="text" class="form-control" id="ar_name" name="ar_name">
        </div>
        <div class="form-group">
            <label for="phone">phone</label>
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

            //url: 'search_cust.php',

            $.ajax({
                url: 'http://171.100.56.194:8999/sac_event/myapi/search_cust.php',
                method: 'GET',
                data: { ar_name: ar_name, phone: phone },
                success: function(data) {
                    $('#results').empty();
                    data.forEach(function(customer) {
                        $('#results').append('<li class="list-group-item">' + customer.ar_name + ' - ' + customer.phone + ' - ' + customer.table_number + '</li>');
                    });
                },
                error: function() {
                    $('#results').empty();
                    $('#results').append('<li class="list-group-item text-danger">There was an error processing your request.</li>');
                }
            });
        });
    });
</script>
</body>
</html>

