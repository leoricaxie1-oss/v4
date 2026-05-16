<?php
/**
 * Tiny helper functions used by every page.
 */

/** HTML-safe string escape. Always use this when echoing user input. */
function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Build an absolute URL from a path relative to the project root.
 *
 *   url('/login')           -> http://localhost/panipone/login
 *   url('/dashboard')       -> http://localhost/panipone/dashboard
 *
 * Falls back to BASE_URL from config.php if it is non-empty; otherwise
 * auto-detects from the request so the same code works whether the
 * project is at htdocs/panipone, htdocs/v5, or the htdocs root.
 */
function url(string $path = '/'): string
{
    $base = BASE_URL !== '' ? rtrim(BASE_URL, '/') : detect_base_url();
    if ($path === '' || $path === '/') {
        return $base . '/';
    }
    return $base . '/' . ltrim($path, '/');
}

function detect_base_url(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // SCRIPT_NAME for the front controller is like /panipone/index.php
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir    = rtrim(str_replace('\\', '/', dirname($script)), '/');
    if ($dir === '.' || $dir === '/') {
        $dir = '';
    }
    return $scheme . '://' . $host . $dir;
}

/** Redirect helper. Halts execution. */
function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

/** Find the path portion of the current request relative to the app. */
function current_route(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    // Strip the app's base path so routing is independent of folder name.
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir    = rtrim(str_replace('\\', '/', dirname($script)), '/');
    if ($dir !== '' && $dir !== '/' && strpos($uri, $dir) === 0) {
        $uri = substr($uri, strlen($dir));
    }
    if ($uri === '' || $uri[0] !== '/') {
        $uri = '/' . $uri;
    }
    return rtrim($uri, '/') ?: '/';
}

/** Quick way to fetch one row. */
function db_one(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

/** Fetch many rows. */
function db_all(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/** Execute INSERT/UPDATE/DELETE, return affected row count. */
function db_run(string $sql, array $params = []): int
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/** Format a MySQL datetime for display. */
function fmt_datetime(?string $dt): string
{
    if (!$dt) return '';
    $ts = strtotime($dt);
    return $ts ? date('M j, Y g:i A', $ts) : e($dt);
}

function fmt_date(?string $dt): string
{
    if (!$dt) return '';
    $ts = strtotime($dt);
    return $ts ? date('M j, Y', $ts) : e($dt);
}
