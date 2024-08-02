<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Winner</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(to bottom, #007bff, #6610f2);
            color: white;
            text-align: center;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 600;
        }

        .winner-card {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>

<body>
<section class="hero">
    <div class="container">
        <div class="card">
            <div class="card-body text-center">
                <h1 class="card-title mb-4">สุ่มรายชื่อผู้โชคดี</h1>
                <button id="start-button" class="btn btn-primary btn-lg mb-4">เริ่มการสุ่ม</button>
                <div id="random-names" class="mb-4"></div>
            </div>
        </div>
        <div class="card winner-card">
            <div class="card-body text-center">
                <h2 class="card-title mb-4">ผู้โชคดี</h2>
                <div id="winner" class="text-light"></div>
            </div>
        </div>
    </div>
</section>

<script>
    async function fetchRandomName() {
        const response = await fetch('fetch_random_name.php');
        const data = await response.json();
        return data.ar_name;
    }

    async function displayRandomNames() {
        const randomNamesDiv = document.getElementById('random-names');
        const winnerDiv = document.getElementById('winner');
        let lastRandomName = '';

        for (let i = 0; i < 100; i++) {
            lastRandomName = await fetchRandomName();
            randomNamesDiv.innerHTML = lastRandomName;
            await new Promise(resolve => setTimeout(resolve, 100));
        }

        winnerDiv.innerHTML = "ผู้โชคดีคือ: " + lastRandomName;
        markWinner(lastRandomName);
    }

    async function markWinner(ar_name) {
        await fetch('mark_winner.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ar_name }),
        });
    }

    document.getElementById('start-button').addEventListener('click', displayRandomNames);
</script>
</body>

</html>
