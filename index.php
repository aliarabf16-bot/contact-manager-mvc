<?php
declare(strict_types=1);

// Front controller: all application requests are routed through this file.
ini_set('display_errors', '0');
ini_set('log_errors', '1');

ini_set('session.use_strict_mode', '1');

$isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: DENY');

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/validation.php';
require_once __DIR__ . '/lib/Database.php';
require_once __DIR__ . '/models/Contact.php';
require_once __DIR__ . '/models/Admin.php';
require_once __DIR__ . '/controllers/ContactController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$action = $_GET['action'] ?? 'list';
$action = is_string($action) ? $action : '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Route table restricts both URLs and HTTP methods.
$routes = [
    'list'   => ['GET', [ContactController::class, 'index']],
    'new'    => ['GET', [ContactController::class, 'createForm']],
    'create' => ['POST', [ContactController::class, 'store']],
    'edit'   => ['GET', [ContactController::class, 'editForm']],
    'update' => ['POST', [ContactController::class, 'update']],
    'delete' => ['POST', [ContactController::class, 'destroy']],
    'login'  => ['GET', [AuthController::class, 'loginForm']],
    'signin' => ['POST', [AuthController::class, 'signIn']],
    'logout' => ['POST', [AuthController::class, 'logout']],
];

try {
    if (!isset($routes[$action])) {
        abort_request(404, 'Page not found.');
    }
    if ($routes[$action][0] !== $method) {
        header('Allow: ' . $routes[$action][0]);
        abort_request(405, 'This HTTP method is not allowed.');
    }
    [$class, $handler] = $routes[$action][1];
    (new $class())->$handler();
} catch (Throwable $error) {
    // Keep connection details, SQL, and filesystem paths out of browser output.
    error_log('Contact Manager error: ' . $error->__toString());
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Server error</title>';
    echo '<body style="font-family:sans-serif;max-width:680px;margin:4rem auto;padding:1rem">';
    echo '<h1>Something went wrong</h1><p>Check your local PHP error log and database setup.</p>';
    echo '<p><a href="index.php?action=login">Go to sign in</a></p></body></html>';
}
