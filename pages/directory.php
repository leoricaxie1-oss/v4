<?php
$page_title = 'Skills, Services & Businesses';

$skills     = db_all("SELECT s.id, s.title, s.category, s.description, u.name AS provider
                      FROM skill_services s
                      JOIN users u ON u.id = s.user_id
                      WHERE s.status = 'approved'
                      ORDER BY s.title");
$businesses = db_all("SELECT b.id, b.name, b.category, b.address, u.name AS owner
                      FROM businesses b
                      JOIN users u ON u.id = b.user_id
                      WHERE b.status = 'approved'
                      ORDER BY b.name");

include __DIR__ . '/../includes/layout/header.php';
?>
<h1>Community Directory</h1>
<p class="muted">Browse approved local skills, services and businesses.</p>

<section class="section">
    <h2>Skills &amp; Services</h2>
    <?php if (!$skills): ?>
        <p class="muted">No approved listings yet.</p>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($skills as $s): ?>
                <article class="card">
                    <span class="badge"><?= e($s['category']) ?></span>
                    <h3><?= e($s['title']) ?></h3>
                    <p class="muted small">Offered by <?= e($s['provider']) ?></p>
                    <p><?= nl2br(e(mb_strimwidth((string) $s['description'], 0, 220, '…'))) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="section">
    <h2>Businesses</h2>
    <?php if (!$businesses): ?>
        <p class="muted">No approved listings yet.</p>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($businesses as $b): ?>
                <article class="card">
                    <span class="badge"><?= e($b['category']) ?></span>
                    <h3><?= e($b['name']) ?></h3>
                    <p class="muted small">Owner: <?= e($b['owner']) ?></p>
                    <p><?= e($b['address']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
