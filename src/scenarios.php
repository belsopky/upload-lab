<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Scenarios - UploadLab</title>

    <link rel="stylesheet" href="style.css">
</head>




<body>


<header>
    <h1>UploadLab</h1>

    <div class="score-badge <?= getScore() >= 700 ? 'complete' : '' ?>">
        <?= getScore() ?> / 700
    </div>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="scenarios.php">Scenarios</a>
    <a href="login.php">Login</a>
</nav>

<main>
    <div class="card wide">
        <h2>Scenarios</h2>

        <div class="scenario-box">
            <h3>
                1 upload1php Unrestricted Upload
                <span style="color:#4dff4d;font-size:12px;">[100 pts]</span>
            </h3>

            <p>No validation on file type or content Upload php and execute directly</p>

            <a href="upload1.php" class="start-link">Start Challenge</a>
        </div>





        <div class="scenario-box">
            <h3>
                2 upload2php Content-Type Bypass
                <span style="color:#4dff4d;font-size:12px;">[100 pts]</span>
            </h3>

            <p>Server checks Content-Type header only not file content or extension</p>

            <a href="upload2.php" class="start-link">Start Challenge</a>
        </div>







        <div class="scenario-box">
            <h3>
                3 upload3php Path Traversal
                <span style="color:#4dff4d;font-size:12px;">[100 pts]</span>
            </h3>

            <p>Avatar upload page filename is injected into path without sanitization</p>

            <a href="upload3.php" class="start-link">Start Challenge</a>
        </div>





        <div class="scenario-box">
            <h3>
                4 upload4php Blacklist Bypass Case
                <span style="color:#4dff4d;font-size:12px;">[100pts]</span>
            </h3>

            <p>Blocks known extensions but forgot case variations</p>

            <a href="upload4.php" class="start-link">Start Challenge</a>
        </div>




        <div class="scenario-box">
            <h3>
                5 upload5php Obfuscated Extension
                <span style="color:#4dff4d;font-size:12px;">[100 pts]</span>
            </h3>

            <p>Checks final extension only but Apache checks all extensions</p>

            <a href="upload5.php" class="start-link">Start Challenge</a>
        </div>



        <div class="scenario-box">
            <h3>
                6 upload6php Polyglot Upload
                <span style="color:#4dff4d;font-size:12px;">[100 pts]</span>
            </h3>

            <p>Checks first bytes for real image header only</p>

            <a href="upload6.php" class="start-link">Start Challenge</a>
        </div>





        <div class="scenario-box">
            <h3>
                7 upload7php Race Condition
                <span style="color:#4dff4d;font-size:12px;">[100 pts]</span>
            </h3>

            <p>File is scanned after being saved to disk there is a time window to exploit</p>

            <a href="upload7.php" class="start-link">Start Challenge</a>
        </div>



    </div>
</main>






<footer>
    UploadLab Training Lab
</footer>

</body>

</html>






