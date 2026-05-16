<?php
/**
 * Session-backed authentication helpers.
 *
 * Users are stored in the `users` table with a bcrypt-hashed password.
 * On login we regenerate the session id to prevent fixation, then stash
 * the user id and role for fast access on subsequent requests.
 */

function auth_attempt(string $email, string $password): bool
{
    $user = db_one(
        'SELECT id, name, email, password_hash, role, status FROM users WHERE email = ? LIMIT 1',
        [$email]
    );
    if (!$user) {
        return false;
    }
    if ($user['status'] !== 'active') {
        flash('error', 'Your account is ' . $user['status'] . '. Please contact the barangay office.');
        return false;
    }
    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['uid']    = (int) $user['id'];
    $_SESSION['uname']  = $user['name'];
    $_SESSION['urole']  = $user['role'];
    return true;
}

function auth_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function auth_check(): bool
{
    return !empty($_SESSION['uid']);
}

function auth_user(): ?array
{
    if (!auth_check()) return null;
    static $cached = null;
    if ($cached === null) {
        $cached = db_one(
            'SELECT id, name, email, role, status, created_at FROM users WHERE id = ? LIMIT 1',
            [$_SESSION['uid']]
        );
    }
    return $cached ?: null;
}

function auth_id(): ?int
{
    return isset($_SESSION['uid']) ? (int) $_SESSION['uid'] : null;
}

function auth_role(): ?string
{
    return $_SESSION['urole'] ?? null;
}

function require_login(): void
{
    if (!auth_check()) {
        flash('error', 'Please sign in to continue.');
        redirect('/login');
    }
}

function require_role(string ...$roles): void
{
    require_login();
    if (!in_array(auth_role(), $roles, true)) {
        http_response_code(403);
        echo 'Forbidden.';
        exit;
    }
}
