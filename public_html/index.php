<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto-detect Laravel app folder as a sibling of public_html.
// This avoids server-side path edits as long as the app folder sits next to public_html.
$parentPath = dirname(__DIR__);
$candidatePaths = [
    $parentPath . '/passmark_app',
    $parentPath . '/app',
    $parentPath . '/laravel',
];

foreach (glob($parentPath . '/*', GLOB_ONLYDIR) as $dir) {
    if (basename($dir) !== 'public_html') {
        $candidatePaths[] = $dir;
    }
}

$appPath = null;
foreach (array_unique($candidatePaths) as $candidate) {
    if (file_exists($candidate . '/bootstrap/app.php') && file_exists($candidate . '/vendor/autoload.php')) {
        $appPath = $candidate;
        break;
    }
}

if ($appPath === null) {
    http_response_code(500);
    echo 'Laravel app folder not found. Place your project folder next to public_html and ensure composer dependencies are installed.';
    exit;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appPath . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appPath . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
