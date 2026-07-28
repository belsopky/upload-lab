<?php

require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) 
    {
    session_start(); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';


    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user && password_verify($password, $user['password']))
         {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header('Location: dashboard.php');
        exit; }

    $error = 'Invalid username or password'; }

?>






<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Login UploadLab</title>

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
    <div class="card">
        <h2>login</h2>


        <?php if ($error): ?>
            <div class="msg error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>



        <form method="POST" action="login.php">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        

        <p class="small">wiener / peter</p>
    </div>
</main>




<footer>
    UploadLab Training Lab
</footer>




</body>

</html>




