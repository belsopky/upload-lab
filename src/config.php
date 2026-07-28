<?php

$dbPath = __DIR__ . '/db.sqlite';
$exists = file_exists($dbPath);

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('cant connect: ' . $e->getMessage());
}

if (!$exists) {
    $pdo->exec("
        CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'user',
            avatar TEXT DEFAULT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP) ");

    $stmt = $pdo->prepare("
        INSERT INTO users (username, password, role)
        VALUES (?, ?, ?) ");

    $stmt->execute([
        'wiener',
        password_hash('peter', PASSWORD_DEFAULT),
        'user'  ]);

    $stmt->execute([
        'carlos',
        password_hash('montoya', PASSWORD_DEFAULT),
        'user'  ]);

    $stmt->execute([
        'administrator',
        password_hash('h7Tz_Qw2mNv9', PASSWORD_DEFAULT),
        'admin']);
}

function getChallengeState($id)
{
    $executed = isChallengeExecuted($id);
    $uploaded = isChallengeUploaded($id);

    if ($executed) { return 'completed'; }

    if ($uploaded) {return 'in_progress';
    }

    return 'not_started';}



function isChallengeExecuted($id)
{
    if (isset($_SESSION['executed'][$id])) {
        return true;
    }

    $marker = __DIR__ . '/.markers/' . $id . '.json';

    if (file_exists($marker)) {
        $data = json_decode(file_get_contents($marker), true);
        return !empty($data);
    }

    return false;
}



function isChallengeUploaded($id)
{
    if (isset($_SESSION['uploaded'][$id])) {
        return true; }

    $marker = __DIR__ . '/.uploads/' . $id . '.json';

    if (file_exists($marker)) {
        $data = json_decode(file_get_contents($marker), true);
        return !empty($data); }

    return false;
}




function getExecutionFile($id)
{
    if (isset($_SESSION['last_execution'][$id])) {
        return $_SESSION['last_execution'][$id];
    }

    $marker = __DIR__ . '/.markers/' . $id . '.json';

    if (file_exists($marker)) {
        $data = json_decode(file_get_contents($marker), true);
        return $data['file'] ?? '';
    }

    return '';
}

function markChallengeUploaded($id, $filename)
{
    $_SESSION['uploaded'][$id] = true;
    $_SESSION['last_upload'][$id] = $filename;

    $dir = __DIR__ . '/.uploads';

    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    @file_put_contents(
        "{$dir}/{$id}.json",
        json_encode([
            'time' => date('c'),
            'file' => $filename,
        ])
    );
}



function getScore()
{
    $total = 0;

    for ($i = 1; $i <= 7; $i++) {
        if (isChallengeExecuted($i)) {
            $total += 100;
        }
    }

    return $total;
}



function getCompletedCount()
{
    $count = 0;

    for ($i = 1; $i <= 7; $i++) 
        {
        if (isChallengeExecuted($i)) {
            $count++;
        }
    }

    return $count;
}























































