<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) 
    {header('Location: login.php');
     exit;}

$challengeId = 3;
$state = getChallengeState($challengeId);
$executed = isChallengeExecuted($challengeId);
$executionFile = getExecutionFile($challengeId);

$msg = '';
$base = __DIR__ . '/uploads3/avatars/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $f = $_FILES['avatar'];

    $rawName = $f['full_path'] ?? $f['name'];
    $target = $base . $rawName;

    if (move_uploaded_file($f['tmp_name'], $target)) {
        markChallengeUploaded($challengeId, $rawName);
        $msg = 'avatar updated ' . $rawName;
    } 

    else {$msg = 'upload failed'; }
}

?>











<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Challenge 3 Path Traversal</title>

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
    <a href="logout.php">Logout</a>
</nav>



<main>
    <div class="card">

        <h2>
            Challenge 3 Path Traversal
            <span style="color:#4dff4d;font-size:14px;">[100 pts]</span>
        </h2>

        <div class="challenge-section">
            <h3>Objective</h3>
            <p>
                Escape the intended upload directory and write a shell to the web root then execute it
            </p>
        </div>

        <div class="challenge-section">
            <h3>Description</h3>
            <p>
                The application uses the raw full_path from the multipart request as the filename without any
                sanitization It prepends this to uploads3/avatars If you control the path you can write anywhere
                on the server
            </p>
        </div>

        <div class="challenge-section">
            <h3>Hints</h3>

            <button class="hint-btn" onclick="toggleHint(1)">Show Hint 1</button>
            <div id="hint1" class="hint-box">
                You must modify the filename parameter in the raw multipart body not from the browser Use Burp Suite
            </div>

            <button class="hint-btn" onclick="toggleHint(2)">Show Hint 2</button>
            <div id="hint2" class="hint-box">
                Use ../../shellphp as the file name to escape from uploads3/avatars and write to the web root Then
                open /shellphp from the root
            </div>
        </div>



        <?php if ($msg): ?>
            <div class="msg <?= str_contains($msg, 'avatar updated') ? 'success' : 'error' ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>


        <?php if ($executed): ?>

            <div class="status-box executed">
                [OK] Exploitation Verified Your shell
                <code><?= htmlspecialchars($executionFile) ?></code>
                was executed successfully from the web root +100 pts
            </div>

            <div class="msg flag">
                CAT-F{upload_03_path_traversal_saly_alal_ELNABY}
            </div>

        <?php else: ?>

            <div class="status-box">
                [PENDING] Status <?= ucfirst(str_replace('_', ' ', $state)) ?>
                Traverse out of the avatars directory upload a shell to the web root and execute it
            </div>

        <?php endif; ?>

        <form method="POST" action="upload3.php" enctype="multipart/form-data" style="margin-top:16px;">
            <label>Choose avatar</label>

            <input type="file" name="avatar" required>

            <button type="submit">Upload</button>
        </form>

        <p class="small">
            avatars get saved under uploads3/avatars the filename part of the multipart request is used directly
        </p>

        <div class="next-challenge">
            <a href="upload4.php">Next Challenge</a>
        </div>

    </div>
</main>





<footer>
    UploadLab Training Lab
</footer>

</body>
</html>




