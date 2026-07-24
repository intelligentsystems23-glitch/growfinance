<?php
$title = 'Page Not Found';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="info-card text-center" style="padding: 2rem;">
    <div class="card-header-simple" style="justify-content: center;">
        <h3>404 - Page Not Found</h3>
    </div>
    <div class="card-body">
        <p>Sorry, the page you are looking for does not exist.</p>
        <p>Use the sidebar to navigate to a valid section.</p>
        <a class="btn btn-primary" href="<?= BASE_URL ?>/dashboard">Return to Dashboard</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php';
