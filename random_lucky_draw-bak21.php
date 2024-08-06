<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>10 ปี สงวนออโต้คาร์</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link href="img/logo/logo.png" rel="icon">
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

        .matrix {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: black;
            overflow: hidden;
            z-index: -2;
            display: none;
        }

        .matrix span {
            position: absolute;
            top: -100px;
            color: #00ff00;
            font-family: monospace;
            font-size: 20px;
            animation: fall linear infinite;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }

        #countdown {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            font-size: 10rem; /* Adjust as needed */
            color: red;
            font-weight: bold;
        }

        .bottom-buttons {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
        }

        .bottom-buttons button {
            flex: 1;
            max-width: calc(50% - 1rem); /* ensures both buttons together take full width with a gap */
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100 relative">

<div id="tsparticles"></div>
<div class="matrix" id="matrix"></div>
<div id="countdown"></div>

<div class="container mx-auto px-4 flex-grow">
    <div class="card mb-8">
        <div class="text-center">
            <img src="img/logo/logo text-01.png" class="mx-auto mb-8 max-w-full" alt="Logo">
            <div id="header-random-names" class="text-blue-900 font-bold text-xl md:text-6xl mb-4">
                10 ปี สงวนออโต้คาร์ Lucky Draw
            </div>
            <div id="random-names" class="text-black-500 font-bold text-xl md:text-3xl mb-4"></div>
            <div id="lucky" class="text-red-500 font-bold text-2xl md:text-4xl mb-4"></div>
            <div id="winner" class="text-green-500 font-bold text-4xl md:text-6xl mb-4"></div>
        </div>
    </div>
</div>

<div class="bottom-buttons">
    <button id="start-button"
            class="relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300">
        <span class="relative w-full py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-opacity-0">เริ่มการสุ่ม</span>
    </button>
    <button id="clear-button"
            class="relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 hidden">
        <span class="relative w-full py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-opacity-0">สุ่มรางวัลต่อไป</span>
    </button>
</div>

<script>
    let matrixAudio;

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
            body: JSON.stringify({ name }),
        });
        const data = await response.json();
        return data;
    }

    async function displayRandomNames() {
        const randomNamesDiv = document.getElementById('random-names');
        const luckyDiv = document.getElementById('lucky');
        const winnerDiv = document.getElementById('winner');
        const countdownDiv = document.getElementById('countdown');
        let lastRandomID = '';

        // Start Matrix effect
        startMatrixEffect();

        for (let i = 0; i < 25; i++) {
            lastRandomID = await fetchRandomID();
            const result1 = Math.random().toString(36).substring(2, 7);
            const result2 = Math.random().toString(36).substring(2, 7);
            randomNamesDiv.innerHTML = result1 + lastRandomID + result2;
            await new Promise(resolve => setTimeout(resolve, 100));
        }

        // Countdown before showing the winner
        await countdown();

        // Stop Matrix effect and sound
        stopMatrixEffect();

        const winnerInfo = await fetchWinnerInfo(lastRandomID);
        randomNamesDiv.innerHTML = "";
        luckyDiv.innerHTML = '**** ผู้โชคดีคือ ****\n';
        winnerDiv.innerHTML = `${winnerInfo.ar_name} (${winnerInfo.province_name})`;

        markWinner(lastRandomID);

        // Show fireworks
        showFireworks();
        toggleButtons();
    }

    async function countdown() {
        let cnt = "";
        const countdownDiv = document.getElementById('countdown');
        const sounds = [
            new Audio('sound/countdown5.mp3'),
            new Audio('sound/countdown4.mp3'),
            new Audio('sound/countdown3.mp3'),
            new Audio('sound/countdown2.mp3'),
            new Audio('sound/countdown1.mp3'),
            new Audio('sound/countdown0.mp3'),
            new Audio('sound/countdown_go.mp3')
        ];

        for (let i = 5; i >= -1; i--) {
            if (i < 0) {
                cnt = "GO";
            } else {
                cnt = i;
            }
            countdownDiv.innerHTML = cnt;
            sounds[5 - i].play();
            await new Promise(resolve => setTimeout(resolve, 1000));
        }
        countdownDiv.innerHTML = '';
    }

    async function markWinner(id) {
        await fetch('mark_winner.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id }),
        });
    }

    function startMatrixEffect() {
        const matrix = document.getElementById('matrix');
        matrix.style.display = 'block';
        let intervalId = setInterval(() => {
            const span = document.createElement('span');
            span.style.left = Math.random() * 100 + 'vw';
            span.style.animationDuration = Math.random() * 1 + 1.5 + 's';
            span.innerHTML = Math.random().toString(36).substring(2, 15);
            matrix.appendChild(span);
        }, 30);

        matrix.dataset.intervalId = intervalId;

        // Play matrix sound
        matrixAudio = new Audio('sound/matrix.mp3');
        matrixAudio.loop = true;
        matrixAudio.play();
    }

    function stopMatrixEffect() {
        const matrix = document.getElementById('matrix');
        clearInterval(matrix.dataset.intervalId);
        matrix.innerHTML = '';
        matrix.style.display = 'none';

        // Stop matrix sound
        if (matrixAudio) {
            matrixAudio.pause();
            matrixAudio.currentTime = 0;
        }
    }

    function showFireworks() {
        const audio = new Audio('sound/fireworks.mp3');
        audio.play();

        const fireworks = tsParticles.load("tsparticles", {
            preset: "fireworks",
        });

        audio.addEventListener('ended', () => {
            fireworks.then(container => {
                container.destroy();
            });
        });
    }

    function toggleButtons() {
        const startButton = document.getElementById('start-button');
        const clearButton = document.getElementById('clear-button');
        startButton.classList.toggle('hidden');
        clearButton.classList.toggle('hidden');
    }

    function clearScreen() {
        window.location.reload();
    }

    document.getElementById('start-button').addEventListener('click', displayRandomNames);
    document.getElementById('clear-button').addEventListener('click', clearScreen);

</script>
</body>

</html>
