<?php
/** Shared HTML header. Pages set $page_title before including this. */
$page_title = $page_title ?? APP_NAME;
$flashes    = flash_pull_all();
$user       = auth_user();
$route      = current_route();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?> · <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(url('/assets/css/style.css')) ?>">
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="<?= e(url('/')) ?>">
            <span class="brand-mark">PO</span>
            <span class="brand-text">
                <strong><?= e(APP_NAME) ?></strong>
                <small><?= e(APP_TAGLINE) ?></small>
            </span>
        </a>
        <nav class="navmenu">
            <a href="<?= e(url('/')) ?>" class="<?= $route === '/' ? 'active' : '' ?>">Home</a>
            <a href="<?= e(url('/announcements')) ?>" class="<?= $route === '/announcements' ? 'active' : '' ?>">Announcements</a>
            <a href="<?= e(url('/directory')) ?>" class="<?= $route === '/directory' ? 'active' : '' ?>">Directory</a>
            <?php if ($user): ?>
                <a href="<?= e(url('/dashboard')) ?>" class="<?= $route === '/dashboard' ? 'active' : '' ?>">Dashboard</a>
                <span class="navuser">Hi, <?= e($user['name']) ?></span>
                <form method="post" action="<?= e(url('/logout')) ?>" class="inline-form">
                    <?= csrf_field() ?>
                    <button class="btn btn-ghost" type="submit">Sign out</button>
                </form>
            <?php else: ?>
                <a href="<?= e(url('/login')) ?>" class="btn btn-outline">Sign in</a>
                <a href="<?= e(url('/register')) ?>" class="btn btn-primary">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container main">
<?php foreach ($flashes as $type => $msgs): ?>
    <?php foreach ($msgs as $msg): ?>
        <div class="alert alert-<?= e($type) ?>"><?= e($msg) ?></div>
    <?php endforeach; ?>
<?php endforeach; ?>
