<?php
$page_title = 'Welcome';

$announcements = db_all(
    "SELECT id, title, body, posted_at
     FROM announcements
     WHERE is_published = 1
     ORDER BY posted_at DESC
     LIMIT 3"
);

$skills_count    = (int) (db_one("SELECT COUNT(*) AS c FROM skill_services WHERE status = 'approved'")['c'] ?? 0);
$business_count  = (int) (db_one("SELECT COUNT(*) AS c FROM businesses WHERE status = 'approved'")['c'] ?? 0);
$residents_count = (int) (db_one("SELECT COUNT(*) AS c FROM users WHERE role = 'resident' AND status = 'active'")['c'] ?? 0);

include __DIR__ . '/../includes/layout/header.php';
?>
<section class="hero">
    <div>
        <h1>Serving the residents of Barangay Panipuan</h1>
        <p class="lead">
            Request documents, file complaints, browse skills and services, and
            stay up to date with announcements — all in one place.
        </p>
        <?php if (!auth_check()): ?>
            <div class="hero-cta">
                <a class="btn btn-primary" href="<?= e(url('/register')) ?>">Create an account</a>
                <a class="btn btn-outline" href="<?= e(url('/login')) ?>">Sign in</a>
            </div>
        <?php else: ?>
            <a class="btn btn-primary" href="<?= e(url('/dashboard')) ?>">Go to dashboard</a>
        <?php endif; ?>
    </div>
    <div class="hero-stats">
        <div class="stat"><span class="stat-num"><?= $residents_count ?></span><span>Registered residents</span></div>
        <div class="stat"><span class="stat-num"><?= $skills_count ?></span><span>Approved skills & services</span></div>
        <div class="stat"><span class="stat-num"><?= $business_count ?></span><span>Listed businesses</span></div>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <h2>Latest announcements</h2>
        <a class="muted-link" href="<?= e(url('/announcements')) ?>">View all →</a>
    </div>

    <?php if (empty($announcements)): ?>
        <p class="muted">No announcements yet.</p>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($announcements as $a): ?>
                <article class="card">
                    <h3><?= e($a['title']) ?></h3>
                    <p class="muted small"><?= e(fmt_datetime($a['posted_at'])) ?></p>
                    <p><?= nl2br(e(mb_strimwidth((string) $a['body'], 0, 220, '…'))) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="section section-features">
    <div class="cards">
        <div class="card">
            <h3>Document requests</h3>
            <p>Apply for Barangay Clearance, Residency, Indigency, and Business Clearance online.</p>
        </div>
        <div class="card">
            <h3>Complaints &amp; blotter</h3>
            <p>Submit complaints with evidence and track mediation or hearing schedules.</p>
        </div>
        <div class="card">
            <h3>Skills &amp; services directory</h3>
            <p>Find approved local providers and connect with them through the directory.</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
