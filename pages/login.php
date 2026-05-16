<?php
$page_title = 'Sign in';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email    = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        flash('error', 'Email and password are required.');
        remember_old(['email' => $email]);
        redirect('/login');
    }

    if (auth_attempt($email, $password)) {
        forget_old();
        flash('success', 'Welcome back!');
        redirect('/dashboard');
    }
    flash('error', 'Invalid email or password.');
    remember_old(['email' => $email]);
    redirect('/login');
}

if (auth_check()) {
    redirect('/dashboard');
}

include __DIR__ . '/../includes/layout/header.php';
?>
<div class="auth-shell">
    <h1>Sign in</h1>
    <p class="muted">Use your registered email and password.</p>

    <form method="post" action="<?= e(url('/login')) ?>" class="form">
        <?= csrf_field() ?>
        <label>
            Email
            <input type="email" name="email" value="<?= e(old('email')) ?>" required autofocus>
        </label>
        <label>
            Password
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn btn-primary">Sign in</button>
    </form>

    <p class="muted small">
        Don't have an account? <a href="<?= e(url('/register')) ?>">Register here</a>.
    </p>
</div>

<?php
forget_old();
include __DIR__ . '/../includes/layout/footer.php';
?>
