<?php
// Get the current working directory
$currentDir = getcwd();

// Get the root directory of the project dynamically
$baseDir = dirname(__DIR__); // Adjusts to the root folder of the project

// Determine the href value based on the current directory
if ($currentDir === $baseDir) {
    $privacyPolicyHref = 'admin/privacy-policy.pdf';
} else {
    $privacyPolicyHref = '../admin/privacy-policy.pdf';
}
?>

<div class="container-fluid">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <div class="col text-center">
            <p class="text-muted small mb-0">© 2025 NBC (Philippines) Car Technology Corporation.</p>
            <p class="text-muted small">
                Our commitment to your privacy is paramount. Please read our <a href="<?= $privacyPolicyHref ?>" target="_blank">Privacy Policy</a> to understand how we manage your personal information.
            </p>
        </div>
    </footer>
</div>