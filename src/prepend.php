<?php

if (defined('PREPEND_GUARD')) {
    return;
}

define('PREPEND_GUARD', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$scriptPath = realpath($_SERVER['SCRIPT_FILENAME'] ?? '');
$docRoot = realpath('/var/www/html');

if (!$scriptPath || strpos($scriptPath, $docRoot) !== 0) {
    return;
}

$relativePath = substr($scriptPath, strlen($docRoot) + 1);

$systemFiles = [
    'index.php',
    'login.php',
    'logout.php',
    'dashboard.php',
    'scenarios.php',
    'upload1.php',
    'upload2.php',
    'upload3.php',
    'upload4.php',
    'upload5.php',
    'upload6.php',
    'upload7.php',
    'config.php',
    'prepend.php',
    'reset.php',
    'style.css',
    'completion.php',
];

if (preg_match('#^uploads(\d+)/#', $relativePath, $matches)) {
    $challengeId = (int) $matches[1];
    $filename = basename($relativePath);

    if ($filename === '.htaccess') {
        return;
    }

    $_SESSION['executed'][$challengeId] = true;
    $_SESSION['last_execution'][$challengeId] = $filename;

    $markerDir = '/var/www/html/.markers';

    if (!is_dir($markerDir)) {
        @mkdir($markerDir, 0777, true);
    }

    @file_put_contents(
        "{$markerDir}/{$challengeId}.json",
        json_encode([
            'time' => date('c'),
            'file' => $filename,
        ])
    );

    return;
}

$basename = basename($relativePath);

if (
    !in_array($basename, $systemFiles, true)
    && substr($relativePath, 0, 8) !== 'uploads'
) {
    $_SESSION['executed'][3] = true;
    $_SESSION['last_execution'][3] = $basename;

    $markerDir = '/var/www/html/.markers';

    if (!is_dir($markerDir)) {
        @mkdir($markerDir, 0777, true);
    }

    @file_put_contents(
        "{$markerDir}/3.json",
        json_encode([
            'time' => date('c'),
            'file' => $basename,
            'note' => 'path_traversal',
        ])
    );
}



