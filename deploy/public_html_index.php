<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Path to Laravel app folder (sibling of public_html)
// On Hostinger: /home/u[id]/passmark_app/
$appPath = __DIR__ . '/../passmark_app';

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
