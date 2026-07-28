<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) 
    
    { session_start(); }

$challengeId = 1;
$state = getChallengeState($challengeId);
$executed = isChallengeExecuted($challengeId);
$executionFile = getExecutionFile($challengeId);

$msg = '';
$dir = __DIR__ . '/uploads1/';  

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $f = $_FILES['file'];
    $target = $dir . $f['name'];

    if (move_uploaded_file($f['tmp_name'], $target)) {
        markChallengeUploaded($challengeId, basename($f['name']));
        $msg = 'File uploaded successfully to uploads1/' . basename($f['name']) . ' Now try to access and execute it';
    } else {
        $msg = 'Upload failed';
    }
}

$files = array_diff(scandir($dir), ['.', '..', '.htaccess']);

?>















<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Challenge 1 - Unrestricted Upload</title>


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
            Challenge 1 Unrestricted Upload
            <span style="color:#4dff4d;font-size:14px;">[100 pts]</span>
        </h2>


        <div class="challenge-section">
            <h3>Objective</h3>
            <p>Upload a PHP web shell and execute it to prove Remote Code Execution</p>
        </div>

        <div class="challenge-section">
            <h3>Description</h3>
            <p>This upload endpoint performs no validation whatsoever on the file type extension or content You can upload any file and access it directly</p>
        </div>

        <div class="challenge-section">
            <h3>Hints</h3>

            <button class="hint-btn" onclick="toggleHint(1)">Show Hint 1</button>

            <div id="hint1" class="hint-box">
                Upload a file with extension <code>php</code> containing PHP code like
                <code>&lt;?php system($_GET['cmd']); ?&gt;</code>
            </div>

            <button class="hint-btn" onclick="toggleHint(2)">Show Hint 2</button>

            <div id="hint2" class="hint-box">
                After uploading click on the file name in the uploaded files list or open it directly from the path
                <code>uploads1/shell.php</code>
                Try adding <code>?cmd=whoami</code> in the URL
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
            CAT-F{upload_01_unrestricted_upload_saly_alal_ELNABY}
            </div>

        <?php else: ?>



            
            <div class="status-box">
                [PENDING] Status <?= ucfirst(str_replace('_', ' ', $state)) ?>
                Upload a shell and execute it to reveal the flag and earn 100 points
            </div>


        <?php endif; ?>

        <form method="POST" action="upload1.php" enctype="multipart/form-data" style="margin-top:16px;">
            <label>Choose file</label>
            <input type="file" name="file" required>
            <button type="submit">Upload</button>
        </form>


        <p class="small">Uploaded files</p>



        <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <a href="uploads1/<?= rawurlencode($file) ?>" target="_blank">
                        <?= htmlspecialchars($file) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="next-challenge">
            <a href="upload2.php">Next Challenge</a>
        </div>

    </div>

</main>




<footer>
    UploadLab Training Lab
</footer>


</body>
</html>






