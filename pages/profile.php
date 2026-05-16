<?php
require_login();
$page_title = 'My profile';
$uid = auth_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name    = trim((string) ($_POST['name'] ?? ''));
    $phone   = trim((string) ($_POST['phone'] ?? ''));
    $address = trim((string) ($_POST['address'] ?? ''));

    if ($name === '') {
        flash('error', 'Full name cannot be empty.');
        redirect('/profile');
    }
    db_run(
        'UPDATE users SET name = ?, phone = ?, address = ?, updated_at = NOW() WHERE id = ?',
        [$name, $phone, $address, $uid]
    );
    $_SESSION['uname'] = $name;
    flash('success', 'Profile updated.');
    redirect('/profile');
}

$me = db_one(
    'SELECT id, name, email, phone, address, role, status FROM users WHERE id = ?',
    [$uid]
);
include __DIR__ . '/../includes/layout/header.php';
?>
<h1>My profile</h1>

<form method="post" action="<?= e(url('/profile')) ?>" class="form">
    <?= csrf_field() ?>
    <label>
        Full name
        <input type="text" name="name" value="<?= e($me['name']) ?>" required>
    </label>
    <label>
        Email
        <input type="email" value="<?= e($me['email']) ?>" disabled>
        <small class="muted">Contact the Barangay office to change your email.</small>
    </label>
    <label>
        Mobile
        <input type="text" name="phone" value="<?= e($me['phone'] ?? '') ?>">
    </label>
    <label>
        Address
        <input type="text" name="address" value="<?= e($me['address'] ?? '') ?>">
    </label>
    <button class="btn btn-primary" type="submit">Save changes</button>
</form>

<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
