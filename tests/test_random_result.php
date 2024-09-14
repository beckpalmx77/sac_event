<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Winner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.min.js"></script>
    <style>
        .btn-custom {
            @apply bg-blue-500 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out;
        }

        .btn-custom:hover {
            @apply bg-blue-700;
        }

        .card {
            @apply bg-white text-blue-900 p-6 shadow-lg rounded-lg;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-white">
<div class="container mx-auto px-4">
    <div class="card">
        <div class="text-center">
            <h1 class="text-2xl font-bold mb-4">สุ่มรายชื่อผู้โชคดี</h1>
            <div id="random-names" class="text-black-500 font-bold text-3xl"></div>
            <div id="winner" class="text-green-500 font-bold text-5xl"></div>
        </div>
    </div>

    <br>
    <br>

    <div class="card">
        <div class="text-center">
            <button id="start-button" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">เริ่มการสุ่ม</span>
            </button>
        </div>
    </div>
</div>

<script>
    async function fetchRandomName() {
        const response = await fetch('fetch_random_name.php');
        const data = await response.json();
        return data.a;
    }

    async function fetchWinnerInfo(name) {
        const response = await fetch('fetch_winner_info.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name }),
        });
        const data = await response.json();
        return data;
    }

    async function displayRandomNames() {
        const randomNamesDiv = document.getElementById('random-names');
        const winnerDiv = document.getElementById('winner');
        let lastRandomName = '';

        for (let i = 0; i < 50; i++) {
            lastRandomName = await fetchRandomName();
            const result1 = Math.random().toString(36).substring(2,7);
            const result2= Math.random().toString(36).substring(2,7);
            randomNamesDiv.innerHTML = result1 + lastRandomName + result2;
            await new Promise(resolve => setTimeout(resolve, 1));
        }
        randomNamesDiv.innerHTML = "ผู้โชคดีคือ";
        const winnerInfo = await fetchWinnerInfo(lastRandomName);
        winnerDiv.innerHTML = `${winnerInfo.ar_name} (${winnerInfo.province_name})`;

        markWinner(lastRandomName);

        showFireworks();
    }

    async function markWinner(name) {
        await fetch('mark_winner.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name }),
        });
    }

    function showFireworks() {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }

    document.getElementById('start-button').addEventListener('click', displayRandomNames);
</script>
</body>
</html>
