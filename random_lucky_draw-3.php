<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Winner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles-preset-fireworks@2/tsparticles.preset.fireworks.bundle.min.js"></script>
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

        #tsparticles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100 relative">

<div id="tsparticles"></div>
<audio id="fireworks-sound" src="sound/fireworks.mp3" loop></audio>
<div class="container mx-auto px-4">
    <div class="card mb-8">
        <div class="text-center">
            <img src="img/logo/logo text-01.png" width="400" height="158" alt="Logo" class="mx-auto mb-8">
            <div id="header-random-names" class="text-black-500 font-bold text-3xl mb-4"><h1>10 ปี สงวนออโต้คาร์ Lucky Draw</h1></div>
            <div id="random-names" class="text-black-500 font-bold text-3xl mb-4"></div>
            <div id="lucky" class="text-red-500 font-bold text-4xl mb-4"></div>
            <div id="winner" class="text-green-500 font-bold text-6xl mb-4"></div>
        </div>
    </div>

    <div class="card">
        <div class="text-center">
            <button id="start-button"
                    class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300">
                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-opacity-0">เริ่มการสุ่ม</span>
            </button>
            <button id="clear-button"
                    class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 hidden">
                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-opacity-0">สุ่มรางวัลต่อไป</span>
            </button>
        </div>
    </div>
</div>

<script>
    async function fetchRandomID() {
        const response = await fetch('fetch_random_name.php');
        const data = await response.json();
        return data.id;
    }

    async function fetchWinnerInfo(name) {
        const response = await fetch('fetch_winner_info.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({name}),
        });
        const data = await response.json();
        return data;
    }

    async function displayRandomNames() {
        const randomNamesDiv = document.getElementById('random-names');
        const luckyDiv = document.getElementById('lucky');
        const winnerDiv = document.getElementById('winner');
        let lastRandomID = '';

        for (let i = 0; i < 10; i++) {
            lastRandomID = await fetchRandomID();
            const result1 = Math.random().toString(36).substring(2, 7);
            const result2 = Math.random().toString(36).substring(2, 7);
            randomNamesDiv.innerHTML = result1 + lastRandomID + result2;
            await new Promise(resolve => setTimeout(resolve, 100));
        }

        const winnerInfo = await fetchWinnerInfo(lastRandomID);
        randomNamesDiv.innerHTML = "";
        luckyDiv.innerHTML = '**** ผู้โชคดีคือ ****\n';
        winnerDiv.innerHTML = `${winnerInfo.ar_name} (${winnerInfo.province_name})`;

        markWinner(lastRandomID);

        showFireworks();
        toggleButtons();
    }

    async function markWinner(id) {
        await fetch('mark_winner.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({id}),
        });
    }

    function showFireworks() {
        const audio = new Audio('fireworks.mp3');
        audio.play();

        tsParticles.load("tsparticles", {
            preset: "fireworks",
        });
    }

    function showFireworks() {
        const fireworksSound = document.getElementById('fireworks-sound');
        tsParticles.load("tsparticles", {
            preset: "fireworks",
        });
        fireworksSound.play();
    }

    function toggleButtons() {
        const startButton = document.getElementById('start-button');
        const clearButton = document.getElementById('clear-button');
        startButton.classList.toggle('hidden');
        clearButton.classList.toggle('hidden');
    }

    function clearScreen() {
        const fireworksSound = document.getElementById('fireworks-sound');
        fireworksSound.pause();
        fireworksSound.currentTime = 0;
        window.location.reload();
    }

    document.getElementById('start-button').addEventListener('click', displayRandomNames);
    document.getElementById('clear-button').addEventListener('click', clearScreen);

</script>
</body>

</html>
