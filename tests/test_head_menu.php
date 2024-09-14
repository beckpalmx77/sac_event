<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Header</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>

    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        header {
            position: fixed;
            width: 100%;
            background: #333;
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: padding 0.3s ease;
            z-index: 1000;
        }

        .header-small {
            padding: 10px 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            transition: background 0.3s ease;
        }

        nav ul li a:hover {
            background: #555;
            border-radius: 4px;
        }

        main {
            margin-top: 80px; /* Make space for the fixed header */
        }

    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const header = document.getElementById('header');

            window.addEventListener('scroll', function () {
                if (window.scrollY > 50) {
                    header.classList.add('header-small');
                } else {
                    header.classList.remove('header-small');
                }
            });
        });
    </script>


</head>
<body>
<header id="header">
    <div class="container">
        <h1>My Website</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section style="height: 1500px;">
        <h2>Content Area</h2>
        <p>Scroll down to see the header resize...</p>
    </section>
</main>

</body>
</html>

