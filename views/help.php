<?php
$title = 'Help & Support';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="info-card">
    <div class="card-header-simple">
        <h3>Help & Support</h3>
        <i class="fas fa-life-ring"></i>
    </div>
    <div class="card-body">
        <p>Welcome to the help center for your ERP system. Use the links below to access guides, policies, and support resources.</p>
        <ul>
            <li><strong>Getting started:</strong> Learn how to create invoices, track expenses, and manage customers.</li>
            <li><strong>Support:</strong> Contact your technical team or administrator for system issues.</li>
            <li><strong>Documentation:</strong> Use the modules in the sidebar to navigate the main system areas.</li>
        </ul>
        <p>If you need immediate assistance, please email <a href="mailto:support@example.com">support@example.com</a> or speak with your system administrator.</p>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php';
