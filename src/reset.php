
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cleanDir($dir)
{
    if (!is_dir($dir)) 
        { return; }

    foreach (scandir($dir) as $object) {
        if ($object === '.' || $object === '..' || $object === '.htaccess') {
            continue; }


        $path = $dir . DIRECTORY_SEPARATOR . $object;

        if (is_dir($path))
             { cleanDir($path);
               @rmdir($path);
               continue; }

        @unlink($path);
    }
}






$dirs = 
[
    __DIR__ . '/uploads1',
    __DIR__ . '/uploads2',
    __DIR__ . '/uploads3/avatars',
    __DIR__ . '/uploads4',
    __DIR__ . '/uploads5',
    __DIR__ . '/uploads6',
    __DIR__ . '/uploads7',
];




foreach ($dirs as $dir) {
    if (!is_dir($dir)) 
        {continue;}

    cleanDir($dir);}



foreach (['.markers', '.uploads'] as $subDir) {
    $markerDir = __DIR__ . '/' . $subDir;

    if (!is_dir($markerDir)) {
        continue;
    }

    foreach (glob($markerDir . '/*') as $file) {
        @unlink($file);
    }
}


@unlink(__DIR__ . '/db.sqlite');

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        
        $params['domain'],
        $params['secure'],
        $params['httponly']

    );



}



session_destroy();

header('Location: index.php?reset=1');
exit;






