<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE)
     {
    session_start();
    }
?>





<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>uploadLaab</title>

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


    <?php if (isset($_SESSION['username'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    <?php endif; ?>


</nav>



<main>
    <div class="card">
        <h2>صل على النبي</h2>

        <p>CTF training lab for File Upload Vulnerabilities</p>

        <p>
            7 challenges
            The goal is to upload a web shell and actually execute it
        </p>

        <div class="progress-bar-container">
            <div
                class="progress-bar-fill"
                style ="width:<?= (getScore() / 700) * 100 ?>%">
            </div>
        </div>


        <p class="small" style="text-align:center;">
            <?= getCompletedCount() ?> / 7 Challenges Completed
        </p>

        <p class="small">
            Start from <a href="scenarios.php">Scenarios</a>
        </p>

        <p class="small">
            To clear everything and start over
            <a href="reset.php" style="color:#ff8080;">Reset Lab</a>
        </p>

    </div>
</main>




<footer>
    UploadLab Training Lab
</footer>


</body>

</html>











































