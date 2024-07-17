<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index");
} else {

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Summary Report</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
        <style>
            body {
                padding: 20px;
            }

            .table-responsive {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>

    <div class="card">
        <div class="card-header text-white bg-primary"><?php echo urldecode($_GET['s']) ?></div>

        <div class="container-fluid">
            <br>
            <form id="jobs-form" class="row g-3 mb-3">
                <div class="col-md-5">
                    <label for="start_date" class="form-label">Click เลือกวันที่ เริ่มต้น:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control">
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label">Click เลือกวันที่ สิ้นสุด:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">ดูรายงาน</button>
                </div>
            </form>
            <div id="report-table" class="table-responsive"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#jobs-form').on('submit', function (e) {
                //alert("Data = " + $(this).serialize());
                e.preventDefault();
                $.ajax({
                    url: 'model/fetch_jobs_data.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        let data = response.data;
                        let totalAmount = response.total;

                        let reportTable = '<table id="jobs-table" class="table table-bordered table-striped">';
                        reportTable += '<thead><tr><th>Employee</th>';

                        let dates = [];
                        for (let employee in data) {
                            for (let date in data[employee]) {
                                if (date !== 'total' && !dates.includes(date)) {
                                    dates.push(date);
                                }
                            }
                        }
                        dates.sort();

                        dates.forEach(date => {
                            reportTable += '<th>' + date + '</th>';
                        });
                        reportTable += '<th>Total</th></tr></thead>';

                        reportTable += '<tbody>';
                        for (let employee in data) {
                            reportTable += '<tr><td>' + employee + '</td>';
                            dates.forEach(date => {
                                let amount = data[employee][date] ? data[employee][date] : 0;
                                reportTable += '<td>' + amount.toFixed(2) + '</td>';
                            });
                            reportTable += '<td>' + data[employee]['total'].toFixed(2) + '</td></tr>';
                        }
                        reportTable += '<tr class="total-row"><td>Total</td>';
                        dates.forEach(date => {
                            let dateTotal = 0;
                            for (let employee in data) {
                                dateTotal += data[employee][date] ? data[employee][date] : 0;
                            }
                            reportTable += '<td>' + dateTotal.toFixed(2) + '</td>';
                        });
                        reportTable += '<td>' + totalAmount.toFixed(2) + '</td></tr>';
                        reportTable += '</tbody></table>';

                        $('#report-table').html(reportTable);
                        $('#jobs-table').DataTable();
                    }
                });
            });
        });
    </script>
    </body>
    </html>

<?php } ?>