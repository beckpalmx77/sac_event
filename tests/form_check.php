<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Check-In Form</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Hotel Check-In Form</h2>
    <form id="checkInForm">
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" placeholder="Enter your first name" required>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" id="lastName" placeholder="Enter your last name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" class="form-control" id="phone" placeholder="Enter your phone number" required>
        </div>
        <div class="form-group">
            <label for="checkinDate">Check-In Date</label>
            <input type="date" class="form-control" id="checkinDate" required>
        </div>
        <div class="form-group">
            <label for="checkoutDate">Check-Out Date</label>
            <input type="date" class="form-control" id="checkoutDate" required>
        </div>
        <div class="form-group">
            <label for="roomType">Room Type</label>
            <select class="form-control" id="roomType" required>
                <option value="">Select room type</option>
                <option value="single">Single</option>
                <option value="double">Double</option>
                <option value="suite">Suite</option>
            </select>
        </div>
        <div class="form-group">
            <label for="specialRequests">Special Requests</label>
            <textarea class="form-control" id="specialRequests" rows="3" placeholder="Enter any special requests"></textarea>
        </div>
        <button type="button" class="btn btn-primary" onclick="submitForm('checkin')">Check In</button>
        <button type="button" class="btn btn-warning" onclick="submitForm('pending')">Pending</button>
        <button type="button" class="btn btn-danger" onclick="submitForm('cancel')">Cancel</button>
    </form>
</div>

<script>
    function submitForm(status) {
        const form = document.getElementById('checkInForm');
        const formData = new FormData(form);
        formData.append('status', status);

        // ตัวอย่างการส่งข้อมูลด้วย Ajax
        fetch('your-server-endpoint', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                alert('Form submitted with status: ' + status);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
