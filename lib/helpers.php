<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function require_csrf(): void
{
    $supplied = $_POST['csrf_token'] ?? '';
    if (!is_string($supplied) || !hash_equals(csrf_token(), $supplied)) {
        abort_request(419, 'Security token expired or invalid. Go back, refresh, and retry.');
    }
}

function current_admin(): ?array
{
    $admin = $_SESSION['admin'] ?? null;
    return is_array($admin) && isset($admin['id'], $admin['username']) ? $admin : null;
}

function require_login(): void
{
    if (current_admin() === null) {
        flash('error', 'Please sign in to make changes.');
        redirect('login');
    }
}

function flash(string $kind, string $message): void
{
    $_SESSION['flash'] = ['kind' => $kind, 'message' => $message];
}

function pull_flash(): ?array
{
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_array($message) ? $message : null;
}

function redirect(string $action, array $params = []): never
{
    $query = http_build_query(['action' => $action] + $params);
    header('Location: index.php?' . $query, true, 303);
    exit;
}

function abort_request(int $status, string $message): never
{
    http_response_code($status);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Error</title>';
    echo '<body style="font-family:sans-serif;max-width:680px;margin:4rem auto;padding:1rem">';
    echo '<h1>Error ' . e($status) . '</h1><p>' . e($message) . '</p>';
    echo '<p><a href="index.php">Return to contacts</a></p></body></html>';
    exit;
}

function contact_id_from_query(): int
{
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!is_int($id)) {
        abort_request(400, 'Invalid contact ID.');
    }
    return $id;
}

function contact_id_from_post(): int
{
    $raw = $_POST['id'] ?? null;
    if (!is_string($raw) && !is_int($raw)) {
        abort_request(400, 'Invalid contact ID.');
    }
    $id = filter_var($raw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!is_int($id)) {
        abort_request(400, 'Invalid contact ID.');
    }
    return $id;
}

function render(string $view, array $data = []): void
{
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    if (!is_file($viewFile)) {
        throw new RuntimeException('Missing view.');
    }
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../views/layout.php';
}
