<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$challengeId = 5;
$state = getChallengeState($challengeId);
$executed = isChallengeExecuted($challengeId);
$executionFile = getExecutionFile($challengeId);

$msg = '';

$dir = __DIR__ . '/uploads5/';
$allowedExt = [
    'jpg',
    'jpeg',
    'png',
    'gif'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    $f = $_FILES['file'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {

        $msg='only jpg/png/gif allowed';

    } else {

        $target = $dir . $f['name'];

        if (move_uploaded_file($f['tmp_name'], $target)) {

            markChallengeUploaded($challengeId, basename($f['name']));
            $msg = 'File uploaded successfully to uploads5/' . basename($f['name']) . ' Now try to access and execute it';

        } else {

            $msg = 'upload failed';
        }
    }
}

$files = array_diff(scandir($dir), ['.', '..', '.htaccess']);

?>







<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Challenge 5 - Obfuscated Extension</title>

    <link rel="stylesheet" href="style.css">

    <script>
        function toggleHint(n) {
            var el = document.getElementById('hint' + n);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }
    </script>
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
    <a href="dashboard.php">Dashboard</a>
</nav>

<main>

    <div class="card">

        <h2>
            Challenge 5 Obfuscated Extension
            <span style="color:#4dff4d;font-size:14px;">[100 pts]</span>
        </h2>

        <div class="challenge-section">
            <h3>Objective</h3>

            <p>
                Abuse Apache multiple extension parsing to execute a PHP file disguised as an image
            </p>
        </div>

        <div class="challenge-section">
            <h3>Description</h3>

            <p>
                The application only validates the final extension like jpg
                However the server is misconfigured with
                AddHandler application/x-httpd-php php
                which parses all extensions in the filename
            </p>
        </div>

        <div class="challenge-section">

            <h3>Hints</h3>

            <button class="hint-btn" onclick="toggleHint(1)">
                Show Hint 1
            </button>

            <div id="hint1" class="hint-box">
                Use a double extension like shellphpjpg
                The final extension jpg will pass the check
            </div>

            <button class="hint-btn" onclick="toggleHint(2)">
                Show Hint 2
            </button>

            <div id="hint2" class="hint-box">
                Apache checks all extensions in the file name
                When it finds php inside the name it will run it as PHP
                even if it ends with jpg
            </div>

        </div>


        
        <?php if ($msg): ?>

            <div class="msg <?= str_contains($msg, 'successfully') ? 'success' : 'error' ?>">
                <?= htmlspecialchars($msg) ?>
            </div>

        <?php endif; ?>


        <?php if ($executed): ?>


            <div class="status-box executed">
                [OK] Exploitation Verified
                Your shell
                <code><?= htmlspecialchars($executionFile) ?></code>
                was executed successfully +100 pts
            </div>

            <div class="msg flag">
                CAT-F{upload_05_obfuscated_extension_saly_alal_ELNABY}
            </div>



        <?php else: ?>

            <div class="status-box">
                [PENDING] Status
                <?= ucfirst(str_replace('_', ' ', $state)) ?>
                Upload a double-extension shell and execute it
            </div>

        <?php endif; ?>

        <form
            method="POST"
            action="upload5.php"
            enctype="multipart/form-data"
            style="margin-top:16px;"
        >
            <label>Choose image</label>

            <input type="file" name="file" required>

            <button type="submit">
                Upload
            </button>
        </form>

        <p class="small">
            checks only the final extension after the last dot
        </p>

        <p class="small">
            Uploaded files
        </p>

        

        <ul>
            <?php foreach ($files as $file): ?>

                <li>
                    <a
                        href="uploads5/<?= rawurlencode($file) ?>"
                        target="_blank"
                    >
                        <?= htmlspecialchars($file) ?>
                    </a>
                </li>

            <?php endforeach; ?>
        </ul>

        <div class="next-challenge">
            <a href="upload6.php">
                Next Challenge
            </a>
        </div>

    </div>

</main>



<footer>
    UploadLab Training Lab
</footer>

</body>

</html>


