<?php
$page_title = 'Announcements';
$items = db_all(
    "SELECT id, title, body, posted_at
     FROM announcements
     WHERE is_published = 1
     ORDER BY posted_at DESC"
);
include __DIR__ . '/../includes/layout/header.php';
?>
<h1>Announcements</h1>

<?php if (empty($items)): ?>
    <p class="muted">No announcements have been posted yet.</p>
<?php else: ?>
    <div class="stack">
        <?php foreach ($items as $a): ?>
            <article class="card">
                <h2><?= e($a['title']) ?></h2>
                <p class="muted small"><?= e(fmt_datetime($a['posted_at'])) ?></p>
                <p><?= nl2br(e($a['body'])) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
