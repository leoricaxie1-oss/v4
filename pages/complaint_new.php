<?php
require_login();
$page_title = 'File a complaint';

$categories = ['Noise', 'Property dispute', 'Public disturbance', 'Theft', 'Other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $subject  = trim((string) ($_POST['subject'] ?? ''));
    $category = (string) ($_POST['category'] ?? '');
    $details  = trim((string) ($_POST['details'] ?? ''));

    $errors = [];
    if ($subject === '')                              $errors[] = 'Subject is required.';
    if (!in_array($category, $categories, true))      $errors[] = 'Pick a valid category.';
    if ($details === '')                              $errors[] = 'Please describe what happened.';

    if ($errors) {
        foreach ($errors as $m) flash('error', $m);
        remember_old(compact('subject', 'category', 'details'));
        redirect('/complaints/new');
    }

    db_run(
        'INSERT INTO complaints (user_id, subject, category, details, status, filed_at)
         VALUES (?, ?, ?, ?, "open", NOW())',
        [auth_id(), $subject, $category, $details]
    );
    forget_old();
    flash('success', 'Your complaint has been filed.');
    redirect('/complaints');
}

include __DIR__ . '/../includes/layout/header.php';
?>
<h1>File a complaint</h1>

<form method="post" action="<?= e(url('/complaints/new')) ?>" class="form">
    <?= csrf_field() ?>
    <label>
        Subject
        <input type="text" name="subject" value="<?= e(old('subject')) ?>" required>
    </label>
    <label>
        Category
        <select name="category" required>
            <option value="">— select —</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= e($c) ?>" <?= old('category') === $c ? 'selected' : '' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        Details
        <textarea name="details" rows="6" required><?= e(old('details')) ?></textarea>
    </label>
    <button type="submit" class="btn btn-primary">Submit complaint</button>
</form>

<?php
forget_old();
include __DIR__ . '/../includes/layout/footer.php';
?>
