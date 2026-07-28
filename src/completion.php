<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start(); }

$score = getScore();
$completed = getCompletedCount();

if ($completed < 7)
    {
    header('Location: dashboard.php');
    exit; }

?>










<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Congratulations ploadLab</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>



<header>
    <h1>uploadLab</h1>

    <div class="score-badge omplete">
        <?= $score ?> / 700
    </div>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="scenarios.php">Scenarios</a>
    <a href="dashboard.php">Dashboard</a>

    <?php if (isset($_SESSION['username'])): ?>
        <a href="logout.php">Logout</a>
    <?php endif; ?>
</nav>


<main>
    <div class="card wide">
        <div class="completion-container">

            <div class="trophy">TROPHY</div>

            <h1>Congratulations</h1>

            <p>
                You have completed UploadLab all 7 challenges have been successfully exploited </p>

            <div class="score-big">
                <?= $score ?> / 700
            </div>

            <div
                class="progress-bar-container"
                style="max-width:400px; margin:20px auto;"
            >
                <div
                    class="progress-bar-fill"
                    style="width:100%;"
                ></div>
            </div>

            
            <p style="color:#4dff4d; font-weight:bold;">
                Excellent All 7 Flags Collected
            </p>

            <p class="small">
                Keep learning Keep hacking ethically
            </p>

            <a href="scenarios.php" class="btn">
                Review Challenges
            </a>

            <a
                href="reset.php"
                class="btn"
                style="margin-left:10px; background:#2a2a2a; border:1px solid #b30000;"
            >
                Reset and Play Again
            </a>

        </div>
    </div>
</main>




<footer>
    UploadLab Training Lab
</footer>

</body>

</html>










