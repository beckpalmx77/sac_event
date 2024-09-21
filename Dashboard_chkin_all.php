<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Attendee List</title>

    <!-- Bootstrap CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        /* Custom styles for mobile view */
        .mobile-hide {
            display: none;
        }

        /* Show hidden columns on larger screens */
        @media (min-width: 768px) {
            .mobile-hide {
                display: table-cell;
            }
        }

        /* Stack table rows for small screens */
        @media (max-width: 767px) {
            .table-responsive td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table-responsive td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                text-align: left;
                font-weight: bold;
            }
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <h2 class="text-center my-4">Attendee List</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>ลำดับที่</th>
                <th>ผู้เข้าร่วมงาน</th>
                <th class="mobile-hide">หมายเลขโต๊ะ</th>
                <th>จังหวัด</th>
                <th class="mobile-hide">สถานะเช็คอิน</th>
                <th class="mobile-hide">เวลาเช็คอิน</th>
            </tr>
            </thead>
            <tbody id="attendee-tbody">
            <!-- Rows will be dynamically inserted here via JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
    // Function to fetch and display attendees
    function fetchAttendees() {
        $.ajax({
            url: 'fetch_attendees.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                const $tbody = $('#attendee-tbody');
                $tbody.empty(); // Clear previous rows

                // Loop through each attendee and append them to the table
                data.forEach(function (attendee) {
                    $tbody.append(
                        `<tr>
                            <td data-label="ลำดับที่">${attendee.order_record}</td>
                            <td data-label="ผู้เข้าร่วมงาน">${attendee.ar_name}</td>
                            <td data-label="หมายเลขโต๊ะ" class="mobile-hide">${attendee.table_number}</td>
                            <td data-label="จังหวัด">${attendee.province_name}</td>
                            <td data-label="สถานะเช็คอิน" class="mobile-hide">${attendee.check_in_status_text}</td>
                            <td data-label="เวลาเช็คอิน" class="mobile-hide">${attendee.update_chk_in_date}</td>
                        </tr>`
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching attendees:', error);
            }
        });
    }

    // Fetch attendees on page load
    $(document).ready(function () {
        fetchAttendees();
    });
</script>
</body>
</html>
