<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$challengeId = 2;
$state = getChallengeState($challengeId);
$executed = isChallengeExecuted($challengeId);
$executionFile = getExecutionFile($challengeId);

$msg = '';

$dir = __DIR__ . '/uploads2/';
$allowed = [
    'image/jpeg',
    'image/png',
    'image/gif'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $f = $_FILES['file'];

    if (!in_array($f['type'], $allowed)) {
        $msg = 'only images allowed (got ' . htmlspecialchars($f['type']) . ')';
    } else {
        $target = $dir . $f['name'];

        if (move_uploaded_file($f['tmp_name'], $target)) {
            markChallengeUploaded($challengeId, basename($f['name']));
            $msg = 'File uploaded successfully to uploads2/' . basename($f['name']) . ' Now try to access and execute it';
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
    <title>Challenge 2 - Content-Type Bypass</title>

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
            Challenge 2 Content-Type Bypass
            <span style="color:#4dff4d;font-size:14px;">[100 pts]</span>
        </h2>

        <div class="challenge-section">
            <h3>Objective</h3>
            <p>Bypass the Content-Type validation to upload and execute a PHP shell</p>
        </div>

        <div class="challenge-section">
            <h3>Description</h3>
            <p>
                The server trusts the Content-Type header sent by the browser
                It only checks this header and does not verify the actual file extension or content
            </p>
        </div>

        <div class="challenge-section">
            <h3>Hints</h3>

            <button class="hint-btn" onclick="toggleHint(1)">Show Hint 1</button>
            <div id="hint1" class="hint-box">
                Use Burp Suite or any proxy to intercept the upload request and modify it
            </div>

            <button class="hint-btn" onclick="toggleHint(2)">Show Hint 2</button>
            <div id="hint2" class="hint-box">
                Change the Content-Type header to image/jpeg and keep the file name as shell.php
                The server only checks the header
            </div>
        </div>




        <?php if ($msg): ?>
            <div class="msg <?= str_contains($msg, 'successfully') ? 'success' : 'error' ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>


        <?php if ($executed): ?>
            <div class="status-box executed">
                [OK] Exploitation Verified Your shell
                <code><?= htmlspecialchars($executionFile) ?></code>
                was executed successfully +100 pts
            </div>

            <div class="msg flag">
                CAT-F{upload_02_content_type_bypass_saly_alal_ELNABY}
            </div>

        <?php else: ?>

            <div class="status-box">
                [PENDING] Status <?= ucfirst(str_replace('_', ' ', $state)) ?>
                Forge the Content-Type upload a PHP shell and execute it
            </div>

        <?php endif; ?>



        <form method="POST" action="upload2.php" enctype="multipart/form-data" style="margin-top:16px;">
            <label>Choose image</label>

            <input type="file" name="file" required>

            <button type="submit">Upload</button>
        </form>


        <p class="small">Uploaded files</p>

        <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <a href="uploads2/<?= rawurlencode($file) ?>" target="_blank">
                        <?= htmlspecialchars($file) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="next-challenge">
            <a href="upload3.php">Next Challenge</a>
        </div>

    </div>
</main>




<footer>
    UploadLab Training Lab
</footer>

</body>
</html>




