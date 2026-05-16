<?php
require_login();
$page_title = 'Dashboard';

$uid = auth_id();
$me  = auth_user();

$docCount       = (int) (db_one('SELECT COUNT(*) c FROM document_requests WHERE user_id = ?', [$uid])['c'] ?? 0);
$complaintCount = (int) (db_one('SELECT COUNT(*) c FROM complaints WHERE user_id = ?', [$uid])['c'] ?? 0);
$noticeCount    = (int) (db_one('SELECT COUNT(*) c FROM notifications WHERE user_id = ? AND read_at IS NULL', [$uid])['c'] ?? 0);

$recentDocs = db_all(
    'SELECT id, document_type, status, requested_at
     FROM document_requests
     WHERE user_id = ?
     ORDER BY requested_at DESC LIMIT 5',
    [$uid]
);

include __DIR__ . '/../includes/layout/header.php';
?>
<h1>Hello, <?= e($me['name']) ?></h1>
<p class="muted">
    Role: <strong><?= e($me['role']) ?></strong> · Status:
    <span class="badge badge-<?= e($me['status']) ?>"><?= e($me['status']) ?></span>
</p>

<?php if ($me['status'] === 'pending'): ?>
    <div class="alert alert-warning">
        Your account is still pending approval by the Barangay Secretary.
        Some features will unlock once approved.
    </div>
<?php endif; ?>

<section class="cards">
    <div class="card stat">
        <span class="stat-num"><?= $docCount ?></span>
        <span>Document requests</span>
        <a class="muted-link" href="<?= e(url('/documents')) ?>">View →</a>
    </div>
    <div class="card stat">
        <span class="stat-num"><?= $complaintCount ?></span>
        <span>Complaints filed</span>
        <a class="muted-link" href="<?= e(url('/complaints')) ?>">View →</a>
    </div>
    <div class="card stat">
        <span class="stat-num"><?= $noticeCount ?></span>
        <span>Unread notifications</span>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <h2>Recent document requests</h2>
        <a class="btn btn-primary" href="<?= e(url('/documents/new')) ?>">New request</a>
    </div>
    <?php if (!$recentDocs): ?>
        <p class="muted">You haven't filed any document requests yet.</p>
    <?php else: ?>
        <table class="table">
            <thead><tr><th>#</th><th>Type</th><th>Status</th><th>Requested</th></tr></thead>
            <tbody>
                <?php foreach ($recentDocs as $d): ?>
                    <tr>
                        <td>#<?= (int) $d['id'] ?></td>
                        <td><?= e($d['document_type']) ?></td>
                        <td><span class="badge badge-<?= e($d['status']) ?>"><?= e($d['status']) ?></span></td>
                        <td><?= e(fmt_datetime($d['requested_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="section">
    <h2>Quick links</h2>
    <ul class="quick-links">
        <li><a href="<?= e(url('/profile')) ?>">Edit profile</a></li>
        <li><a href="<?= e(url('/documents/new')) ?>">Request a document</a></li>
        <li><a href="<?= e(url('/complaints/new')) ?>">File a complaint</a></li>
        <li><a href="<?= e(url('/directory')) ?>">Browse skills &amp; businesses</a></li>
    </ul>
</section>

<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
