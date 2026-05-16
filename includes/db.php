<?php
/**
 * PDO connection — opened lazily, reused for the rest of the request.
 *
 * If the connection fails (wrong credentials, MySQL not running, database
 * not created in phpMyAdmin yet) we render a friendly setup page instead
 * of dumping a raw PDO exception.
 */

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        render_db_error_page($e);
        exit(1);
    }

    return $pdo;
}

function render_db_error_page(PDOException $e): void
{
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    $msg  = APP_DEBUG ? htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') : 'Database unavailable.';
    $host = htmlspecialchars(DB_HOST . ':' . DB_PORT, ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars(DB_NAME, ENT_QUOTES, 'UTF-8');
    $user = htmlspecialchars(DB_USER, ENT_QUOTES, 'UTF-8');
    echo <<<HTML
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><title>PanipOne — database setup required</title>
<style>body{font-family:system-ui,sans-serif;max-width:720px;margin:4rem auto;padding:0 1.5rem;color:#1f2937}
h1{color:#b91c1c;margin-bottom:.5rem}code,pre{background:#f3f4f6;border-radius:6px;padding:.15rem .4rem}
pre{padding:1rem;overflow-x:auto}ul{line-height:1.7}</style></head><body>
<h1>Cannot connect to MySQL</h1>
<p>The application could not reach the database. Check that:</p>
<ul>
  <li>The XAMPP <strong>MySQL</strong> module is started.</li>
  <li>A database named <code>{$name}</code> exists in
    <a href="http://localhost/phpmyadmin">phpMyAdmin</a>.</li>
  <li>Credentials match: host <code>{$host}</code>, user <code>{$user}</code>.</li>
</ul>
<p>To set up the schema, open phpMyAdmin, create a database called
   <code>{$name}</code> with collation <code>utf8mb4_unicode_ci</code>, then
   <strong>Import</strong> the file <code>database/panipone.sql</code> from
   this project. Adjust credentials in <code>config.php</code> if yours differ
   from the XAMPP defaults.</p>
<p><small>{$msg}</small></p>
</body></html>
HTML;
}
