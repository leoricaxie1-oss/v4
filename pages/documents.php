<?php
require_login();
$page_title = 'Document requests';
$uid = auth_id();

$rows = db_all(
    'SELECT id, document_type, purpose, status, requested_at, picked_up_at
     FROM document_requests
     WHERE user_id = ?
     ORDER BY requested_at DESC',
    [$uid]
);

include __DIR__ . '/../includes/layout/header.php';
?>
<div class="section-head">
    <h1>Document requests</h1>
    <a class="btn btn-primary" href="<?= e(url('/documents/new')) ?>">New request</a>
</div>

<?php if (!$rows): ?>
    <p class="muted">You haven't filed any document requests yet.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr><th>#</th><th>Type</th><th>Purpose</th><th>Status</th><th>Requested</th><th>Picked up</th></tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><?= e($r['document_type']) ?></td>
                    <td><?= e($r['purpose']) ?></td>
                    <td><span class="badge badge-<?= e($r['status']) ?>"><?= e($r['status']) ?></span></td>
                    <td><?= e(fmt_datetime($r['requested_at'])) ?></td>
                    <td><?= e(fmt_datetime($r['picked_up_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
