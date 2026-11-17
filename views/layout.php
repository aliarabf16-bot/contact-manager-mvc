<?php
declare(strict_types=1);
$notice = pull_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Contact Manager') ?> · Contact Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container nav">
            <a class="brand" href="index.php">Contact<span>Manager</span></a>
            <nav class="nav-actions" aria-label="Main navigation">
                <a href="index.php?action=list">Contacts</a>
                <?php if (current_admin() !== null): ?>
                    <span class="admin-label">Signed in as <?= e(current_admin()['username']) ?></span>
                    <form action="index.php?action=logout" method="post" class="inline-form">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <button class="link-button" type="submit">Sign out</button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-outline" href="index.php?action=login">Admin sign in</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container main-content">
        <?php if ($notice !== null): ?>
            <div class="notice <?= ($notice['kind'] ?? '') === 'success' ? 'notice-success' : 'notice-error' ?>" role="status">
                <?= e($notice['message'] ?? '') ?>
            </div>
        <?php endif; ?>
        <?php require $viewFile; ?>
    </main>
    <footer class="container footer">Built with plain PHP, MySQL PDO, and custom MVC.</footer>
</body>
</html>
