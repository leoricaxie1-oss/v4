<?php
/**
 * Front controller / simple router.
 *
 * Every request enters here (via .htaccess rewriting) and is dispatched
 * to a page file under pages/ based on the URL path. Pages are plain
 * PHP files that include the layout header/footer themselves.
 */

require_once __DIR__ . '/includes/bootstrap.php';

$route  = current_route();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Static asset short-circuit: if Apache forwarded an /assets/... request to
// us by mistake (no .htaccess, mod_rewrite disabled, etc), stream the file
// straight back instead of 404-ing.
if (strpos($route, '/assets/') === 0) {
    $file = __DIR__ . $route;
    if (is_file($file)) {
        $mime = match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'svg'  => 'image/svg+xml',
            'png'  => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'ico'  => 'image/x-icon',
            default => 'application/octet-stream',
        };
        header('Content-Type: ' . $mime);
        readfile($file);
        exit;
    }
}

// Route table: path => page file (relative to pages/). The same file
// handles both GET and POST; it inspects $_SERVER['REQUEST_METHOD'].
$routes = [
    '/'                 => 'home.php',
    '/announcements'    => 'announcements.php',
    '/directory'        => 'directory.php',
    '/login'            => 'login.php',
    '/register'         => 'register.php',
    '/logout'           => 'logout.php',
    '/dashboard'        => 'dashboard.php',
    '/profile'          => 'profile.php',
    '/documents'        => 'documents.php',
    '/documents/new'    => 'document_new.php',
    '/complaints'       => 'complaints.php',
    '/complaints/new'   => 'complaint_new.php',
];

$page = $routes[$route] ?? '404.php';
$file = __DIR__ . '/pages/' . $page;

if (!is_file($file)) {
    $file = __DIR__ . '/pages/404.php';
    http_response_code(404);
}

require $file;
