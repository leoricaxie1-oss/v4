<?php
require_login();
$page_title = 'Request a document';

$types = [
    'Barangay Clearance',
    'Certificate of Residency',
    'Certificate of Indigency',
    'Business Clearance',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $type    = (string) ($_POST['document_type'] ?? '');
    $purpose = trim((string) ($_POST['purpose'] ?? ''));

    if (!in_array($type, $types, true)) {
        flash('error', 'Please choose a valid document type.');
        redirect('/documents/new');
    }
    if ($purpose === '') {
        flash('error', 'Please enter a purpose.');
        redirect('/documents/new');
    }

    db_run(
        'INSERT INTO document_requests (user_id, document_type, purpose, status, requested_at)
         VALUES (?, ?, ?, "pending", NOW())',
        [auth_id(), $type, $purpose]
    );
    flash('success', 'Your document request has been submitted.');
    redirect('/documents');
}

include __DIR__ . '/../includes/layout/header.php';
?>
<h1>Request a document</h1>

<form method="post" action="<?= e(url('/documents/new')) ?>" class="form">
    <?= csrf_field() ?>
    <label>
        Document type
        <select name="document_type" required>
            <option value="">— select —</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= e($t) ?>"><?= e($t) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        Purpose
        <textarea name="purpose" rows="4" required></textarea>
    </label>
    <button type="submit" class="btn btn-primary">Submit request</button>
</form>

<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
