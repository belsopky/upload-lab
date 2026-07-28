<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$challengeId = 6;
$state = getChallengeState($challengeId);
$executed = isChallengeExecuted($challengeId);
$executionFile = getExecutionFile($challengeId);

$msg = '';
$dir = __DIR__ . '/uploads6/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $f = $_FILES['file'];
    $check = @getimagesize($f['tmp_name']);

    if ($check === false) {
        $msg = 'file does not look like a valid image';
    } else {
        $target = $dir . $f['name'];

        if (move_uploaded_file($f['tmp_name'], $target)) {
            markChallengeUploaded($challengeId, basename($f['name']));
            $msg = 'File uploaded successfully to uploads6/' . basename($f['name']) . ' Now try to access and execute it';
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
    <title>Challenge 6 - Polyglot Upload</title>

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
            Challenge 6 Polyglot Upload
            <span style="color:#4dff4d;font-size:14px;">[100 pts]</span>
        </h2>

        <div class="challenge-section">
            <h3>Objective</h3>
            <p>
                Bypass image header validation by creating a polyglot file that looks like an image but executes as PHP
            </p>
        </div>

        <div class="challenge-section">
            <h3>Description</h3>
            <p>
                The server uses getimagesize to verify the file starts with valid image magic bytes It does not inspect the rest of the file content
            </p>
        </div>

        <div class="challenge-section">
            <h3>Hints</h3>

            <button class="hint-btn" onclick="toggleHint(1)">Show Hint 1</button>
            <div id="hint1" class="hint-box">
                Start the file with GIF magic bytes GIF89a and then write PHP code
            </div>

            <button class="hint-btn" onclick="toggleHint(2)">Show Hint 2</button>
            <div id="hint2" class="hint-box">
                Upload the file with extension php so Apache runs it The content should start with GIF89a and then contain PHP code
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
                CAT-F{upload_06_polyglot_upload_saly_alal_ELNABY}
            </div>

        <?php else: ?>

            <div class="status-box">
                [PENDING] Status <?= ucfirst(str_replace('_', ' ', $state)) ?>
                Upload a polyglot image/PHP file and execute it
            </div>

        <?php endif; ?>

        <form
            method="POST"
            action="upload6.php"
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
            uses getimagesize to confirm its a real image before saving
        </p>

        <p class="small">
            Uploaded files
        </p>

        <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <a href="uploads6/<?= rawurlencode($file) ?>" target="_blank">
                        <?= htmlspecialchars($file) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="next-challenge">
            <a href="upload7.php">Next Challenge</a>
        </div>

    </div>

</main>




<footer>
    UploadLab Training Lab
</footer>

</body>
</html>


