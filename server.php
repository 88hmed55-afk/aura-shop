<?php

/**
 * Vercel serverless entry for Laravel.
 *
 * Redirects the writable Laravel storage paths to the ephemeral /tmp
 * directory, copies the seeded SQLite database into /tmp so reads (and
 * short-lived writes) work on the serverless runtime, then boots Laravel.
 */

$tmp = sys_get_temp_dir() . '/laravel';

if (!is_dir($tmp)) {
    @mkdir($tmp, 0777, true);
}
foreach (['framework', 'framework/cache', 'framework/data', 'framework/sessions', 'framework/testing', 'framework/views', 'logs'] as $sub) {
    if (!is_dir($tmp . '/' . $sub)) {
        @mkdir($tmp . '/' . $sub, 0777, true);
    }
}

// Copy the committed SQLite database into writable /tmp so the store loads.
$dbSource = __DIR__ . '/database/database.sqlite';
$dbDest = $tmp . '/database.sqlite';
if (file_exists($dbSource) && !file_exists($dbDest)) {
    @copy($dbSource, $dbDest);
}
if (file_exists($dbDest)) {
    putenv('DB_DATABASE=' . $dbDest);
    $_ENV['DB_DATABASE'] = $dbDest;
    $_SERVER['DB_DATABASE'] = $dbDest;
}

// Serve real static assets (images, fonts, compiled build) directly.
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');
if ($uri !== '/' && file_exists($file = __DIR__ . '/public' . $uri) && !is_dir($file)) {
    if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'php') {
        require $file;
    } else {
        header('Content-Type: ' . get_mime_type($file));
        readfile($file);
    }
    return;
}

require __DIR__ . '/vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require __DIR__ . '/bootstrap/app.php';

// Point every framework storage path at the writable /tmp directory.
$app->useStoragePath($tmp);

$app->handleRequest(\Illuminate\Http\Request::capture());

function get_mime_type($filename)
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mimes = [
        'txt' => 'text/plain', 'html' => 'text/html', 'php' => 'text/html',
        'css' => 'text/css', 'js' => 'application/javascript', 'json' => 'application/json',
        'xml' => 'application/xml', 'png' => 'image/png', 'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'webp' => 'image/webp',
        'gif' => 'image/gif', 'bmp' => 'image/bmp', 'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff', 'svg' => 'image/svg+xml', 'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed', 'pdf' => 'application/pdf',
        'mp3' => 'audio/mpeg', 'mp4' => 'video/mp4', 'mov' => 'video/quicktime',
        'ttf' => 'application/x-font-ttf', 'woff' => 'application/x-woff',
        'woff2' => 'font/woff2', 'otf' => 'font/otf', 'eot' => 'application/vnd.ms-fontobject',
    ];

    return $mimes[$extension] ?? 'application/octet-stream';
}
