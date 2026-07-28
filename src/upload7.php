<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$challengeId = 7;
$state = getChallengeState($challengeId);
$executed = isChallengeExecuted($challengeId);
$executionFile = getExecutionFile($challengeId);

$msg = '';
$dir = __DIR__ . '/uploads7/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $f = $_FILES['file'];
    $target = $dir . $f['name'];

    move_uploaded_file($f['tmp_name'], $target);

    usleep(1000000);

    $check = @getimagesize($target);

    if ($check === false) {
        unlink($target);
        $msg = 'file rejected after scan not a valid image';
    } else {
        markChallengeUploaded($challengeId, basename($f['name']));
        $msg = 'File uploaded successfully to uploads7/' . basename($f['name']) . ' Now try to access and execute it';
    }
}

$files = array_diff(scandir($dir), ['.', '..', '.htaccess']);

?>





<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Challenge 7 - Race Condition</title>

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
            Challenge 7 Race Condition
            <span style="color:#4dff4d;font-size:14px;">[100 pts]</span>
        </h2>

        <div class="challenge-section">
            <h3>Objective</h3>
            <p>
                Win the race between file upload and validation deletion to execute your shell
            </p>
        </div>

        <div class="challenge-section">
            <h3>Description</h3>
            <p>
                The file is written to disk immediately then scanned after 1 second.
                If it fails the image validation it is deleted.
                There is a 1second window where the file exists on disk and can be accessed.
            </p>
        </div>

        <div class="challenge-section">
            <h3>Hints</h3>

            <button class="hint-btn" onclick="toggleHint(1)">Show Hint 1</button>
            <div id="hint1" class="hint-box">
                Send the upload request and at the same time send multiple GET requests to the same file.
                Use a script or Turbo Intruder
            </div>

            <button class="hint-btn" onclick="toggleHint(2)">Show Hint 2</button>
            <div id="hint2" class="hint-box">
                The file name is fixed and not changing for example shellphp so you can brute-force
                the link uploads7/shellphp before it gets deleted
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
                was executed successfully before deletion +100 pts
            </div>

            <div class="msg flag">
                CAT-F{upload_07_race_condition_saly_alal_ELNABY}
            </div>


        <?php else: ?>

            <div class="status-box">
                [PENDING] Status
                <?= ucfirst(str_replace('_', ' ', $state)) ?>
                Upload a non-image PHP file and access it within the 1-second scan window
            </div>

        <?php endif; ?>



        <form method="POST"
              action="upload7.php"
              enctype="multipart/form-data"
              style="margin-top:16px;">

            <label>Choose image</label>

            <input type="file" name="file" required>

            <button type="submit">Upload</button>
        </form>

        <p class="small">
            file gets scanned and removed if invalid takes a bit
        </p>

        <p class="small">
            Uploaded files
        </p>


        
        <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <a href="uploads7/<?= rawurlencode($file) ?>" target="_blank">
                        <?= htmlspecialchars($file) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="next-challenge">
            <a href="scenarios.php">All Challenges Completed</a>
        </div>

    </div>
</main>







<footer>
    UploadLab Training Lab
</footer>

</body>
</html>



