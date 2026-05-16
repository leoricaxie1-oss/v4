<?php
require_login();
$page_title = 'Complaints';
$uid = auth_id();

$rows = db_all(
    'SELECT id, subject, category, status, filed_at
     FROM complaints
     WHERE user_id = ?
     ORDER BY filed_at DESC',
    [$uid]
);

include __DIR__ . '/../includes/layout/header.php';
?>
<div class="section-head">
    <h1>My complaints</h1>
    <a class="btn btn-primary" href="<?= e(url('/complaints/new')) ?>">File complaint</a>
</div>

<?php if (!$rows): ?>
    <p class="muted">No complaints filed.</p>
<?php else: ?>
    <table class="table">
        <thead><tr><th>#</th><th>Subject</th><th>Category</th><th>Status</th><th>Filed</th></tr></thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><?= e($r['subject']) ?></td>
                    <td><?= e($r['category']) ?></td>
                    <td><span class="badge badge-<?= e($r['status']) ?>"><?= e($r['status']) ?></span></td>
                    <td><?= e(fmt_datetime($r['filed_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
