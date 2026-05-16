<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$autoload = __DIR__.'/../vendor/autoload.php';

if (! file_exists($autoload)) {
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    $projectRoot = htmlspecialchars(realpath(__DIR__.'/..') ?: dirname(__DIR__), ENT_QUOTES, 'UTF-8');
    echo <<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PanipOne — setup required</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 720px; margin: 4rem auto; padding: 0 1.5rem; color: #1f2937; }
        h1 { color: #b91c1c; margin-bottom: 0.5rem; }
        code, pre { background: #f3f4f6; border-radius: 6px; padding: 0.15rem 0.4rem; }
        pre { padding: 1rem; overflow-x: auto; }
        .path { color: #374151; }
    </style>
</head>
<body>
    <h1>Composer dependencies are missing</h1>
    <p>
        Laravel cannot start because <code>vendor/autoload.php</code> does not exist
        at <span class="path">{$projectRoot}</span>.
    </p>
    <p>Open a terminal in the project folder and run:</p>
    <pre>composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed</pre>
    <p>
        XAMPP does not ship Composer — install it from
        <a href="https://getcomposer.org/download/">getcomposer.org</a>
        and re-run the commands above. See <code>README.md</code> for the full
        XAMPP setup walkthrough.
    </p>
</body>
</html>
HTML;
    exit(1);
}

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require $autoload;

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
