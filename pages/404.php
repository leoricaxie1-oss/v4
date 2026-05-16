<?php
http_response_code(404);
$page_title = 'Not found';
include __DIR__ . '/../includes/layout/header.php';
?>
<div class="empty-state">
    <h1>404 · Page not found</h1>
    <p class="muted">The page you requested doesn't exist.</p>
    <a class="btn btn-primary" href="<?= e(url('/')) ?>">Back to home</a>
</div>
<?php include __DIR__ . '/../includes/layout/footer.php'; ?>
