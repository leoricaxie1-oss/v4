<?php
$page_title = 'Register';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $name    = trim((string) ($_POST['name'] ?? ''));
    $email   = trim((string) ($_POST['email'] ?? ''));
    $phone   = trim((string) ($_POST['phone'] ?? ''));
    $address = trim((string) ($_POST['address'] ?? ''));
    $pw      = (string) ($_POST['password'] ?? '');
    $pw2     = (string) ($_POST['password_confirmation'] ?? '');

    $errors = [];
    if ($name === '')                              $errors[] = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
    if (strlen($pw) < 8)                            $errors[] = 'Password must be at least 8 characters.';
    if ($pw !== $pw2)                               $errors[] = 'Password confirmation does not match.';

    if (!$errors) {
        $existing = db_one('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
        if ($existing) $errors[] = 'An account with this email already exists.';
    }

    if ($errors) {
        foreach ($errors as $msg) flash('error', $msg);
        remember_old(compact('name', 'email', 'phone', 'address'));
        redirect('/register');
    }

    $hash = password_hash($pw, PASSWORD_BCRYPT);
    db_run(
        'INSERT INTO users (name, email, phone, address, password_hash, role, status, created_at)
         VALUES (?, ?, ?, ?, ?, "resident", "pending", NOW())',
        [$name, $email, $phone, $address, $hash]
    );

    forget_old();
    flash('success', 'Registration submitted. The Barangay Secretary will review your account shortly.');
    redirect('/login');
}

if (auth_check()) {
    redirect('/dashboard');
}

include __DIR__ . '/../includes/layout/header.php';
?>
<div class="auth-shell">
    <h1>Create an account</h1>
    <p class="muted">
        New residents register here. Accounts must be approved by the Barangay
        Secretary before you can sign in.
    </p>

    <form method="post" action="<?= e(url('/register')) ?>" class="form">
        <?= csrf_field() ?>
        <label>
            Full name
            <input type="text" name="name" value="<?= e(old('name')) ?>" required autofocus>
        </label>
        <label>
            Email
            <input type="email" name="email" value="<?= e(old('email')) ?>" required>
        </label>
        <label>
            Mobile (optional)
            <input type="text" name="phone" value="<?= e(old('phone')) ?>" placeholder="09xx xxx xxxx">
        </label>
        <label>
            Address (optional)
            <input type="text" name="address" value="<?= e(old('address')) ?>" placeholder="Purok, Street">
        </label>
        <div class="grid-2">
            <label>
                Password
                <input type="password" name="password" required minlength="8">
            </label>
            <label>
                Confirm password
                <input type="password" name="password_confirmation" required minlength="8">
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <p class="muted small">
        Already have an account? <a href="<?= e(url('/login')) ?>">Sign in here</a>.
    </p>
</div>

<?php
forget_old();
include __DIR__ . '/../includes/layout/footer.php';
?>
