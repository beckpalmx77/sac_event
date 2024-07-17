<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta cust_id="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Ajax Example</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <form id="searchForm" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="selectOption">Select Option</label>
                <select id="selectOption" class="form-control">
                    <option value="">Choose...</option>
                    <option value="option1">Option 1</option>
                    <option value="option2">Option 2</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="searchText">Search Text</label>
                <input type="text" class="form-control" id="searchText" placeholder="Enter text">
            </div>
            <div class="form-group col-md-4 align-self-end">
                <button type="button" id="searchBtn" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <table id="example" class="display" style="width:100%">
        <thead>
        <tr>
            <th>cust_id</th>
            <th>ar_name</th>
            <th>cust_name_1</th>
            <th>phone</th>
            <th>province_name</th>
            <th>sale_contact_name</th>
        </tr>
        </thead>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        let table = $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "model/fetch_data.php",
                "type": "POST",
                "data": function ( d ) {
                    d.selectOption = $('#selectOption').val();
                    d.searchText = $('#searchText').val();
                }
            },
            "columns": [
                { "data": "cust_id" },
                { "data": "ar_name" },
                { "data": "cust_name_1" },
                { "data": "phone" },
                { "data": "province_name" },
                { "data": "sale_contact_name" }
            ]
        });

        $('#searchBtn').on('click', function() {
            table.draw();
        });
    });
</script>
</body>
</html>
