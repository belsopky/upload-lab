<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$challenges = [
    1 => ['name' => 'Unrestricted Upload', 'diff' => 'Easy'],
    2 => ['name' => 'Content-Type Bypass', 'diff' => 'Easyyy'],
    3 => ['name' => 'Path Traversal', 'diff' => 'Medium'],
    4 => ['name' => 'Blacklist Bypass', 'diff' => 'Medium'],
    5 => ['name' => 'Obfuscated Extension', 'diff' => 'Mediummm'],
    6 => ['name' => 'Polyglot Upload', 'diff' => 'Hard'],
    7 => ['name' => 'Race Condition', 'diff' => 'Harddd'],    ];


$score = getScore();
$completed = getCompletedCount();
$percent = round(($completed / 7) * 100);



if ($completed >= 7) {
    header('Location: completion.php');
    exit;
}

?>














<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">

    <title>Dashboard - UploadLab</title>

    <link rel="stylesheet" href="style.css">
</head>




<body>

<header>
    <h1>UploadLab</h1>

    <div class="score-badge <?= $score >= 700 ? 'complete' : '' ?>">
        <?= $score ?> / 700
    </div>
</header>



<nav>
    <a href="index.php">Home</a>
    <a href="scenarios.php">Scenarios</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="upload3.php">Update Avatar</a>
    <a href="logout.php">Logout</a>
</nav>


<main>
    <div class="card wide">

        <h2>Welcome <?= htmlspecialchars($_SESSION['username']) ?></h2>

        <table>
            <tr>
                <td>Username</td>
                <td><?= htmlspecialchars($_SESSION['username']) ?></td>
            </tr>
            <tr>
                <td>Role</td>
                <td><?= htmlspecialchars($_SESSION['role']) ?></td>
            </tr>
            <tr>
                <td>Score</td>
                <td><strong><?= $score ?> / 700</strong></td>
            </tr>
            <tr>
                <td>Progress</td>
                <td><?= $completed ?> / 7 (<?= $percent ?>%)</td>
            </tr>
        </table>



        <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width:<?= $percent ?>%"></div>
        </div>


        <h3 style="color:#fff;margin-top:24px;">Challenges</h3>


        <?php
        foreach ($challenges as $id => $info)
             {

            $state = getChallengeState($id);
            $badgeClass = $state;
            $badgeText = str_replace('_', ' ', $state);

            $icon = $state === 'completed'
                ? 'OK'
                : ($state === 'in_progress' ? '~' : ' ');

            $linkColor = $state === 'completed'
                ? '#4cc74c'
                : ($state === 'in_progress' ? '#ffcc00' : '#8a8989');

        ?>




            <div class="status-item">
                <div class="status-label">

                    <span style="font-size:16px;">[<?= $icon ?>]</span>

                    <a href="upload<?= $id ?>.php"
                       style="color:<?= $linkColor ?>;text-decoration:none;font-weight:bold;">
                        <?= $id ?> <?= htmlspecialchars($info['name']) ?>
                    </a>

                    <span style="font-size:11px;color:#777;">
                        [<?= $info['diff'] ?>]
                    </span>

                </div>

                <span class="status-badge <?= $badgeClass ?>">
                    <?= $badgeText ?>
                </span>
            </div>

        <?php } ?>

        

        <p style="margin-top:18px;">
            <a href="reset.php" class="reset-link">Reset Lab</a>
        </p>

    </div>
</main>





<footer>
    UploadLab Training Lab
</footer>

</body>
</html>








