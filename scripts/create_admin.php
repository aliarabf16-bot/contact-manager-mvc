<?php
declare(strict_types=1);

// Run from terminal: php scripts/create_admin.php
// Never store a password in SQL, in Git, or on the command line.
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Command-line only.');
}

require_once __DIR__ . '/../lib/Database.php';

function prompt(string $label, bool $hidden = false): string
{
    fwrite(STDOUT, $label);
    $canHide = $hidden && DIRECTORY_SEPARATOR !== '\\' && function_exists('shell_exec')
        && trim((string) shell_exec('command -v stty 2>/dev/null')) !== '';
    if ($canHide) {
        // This is a local CLI utility; restore terminal echo even when reading fails.
        shell_exec('stty -echo');
    }
    try {
        $value = fgets(STDIN);
    } finally {
        if ($canHide) {
            shell_exec('stty echo');
            fwrite(STDOUT, PHP_EOL);
        }
    }
    return trim($value === false ? '' : $value);
}

try {
    $username = prompt('Admin username (3-50 letters/numbers/._-): ');
    if (preg_match('/^[A-Za-z0-9_.-]{3,50}$/D', $username) !== 1) {
        throw new RuntimeException('Invalid username.');
    }
    $password = prompt('Admin password (minimum 12 characters): ', true);
    if (strlen($password) < 12 || strlen($password) > 72) {
        throw new RuntimeException('Password must be 12–72 bytes long.');
    }
    $confirmed = prompt('Confirm password: ', true);
    if (!hash_equals($password, $confirmed)) {
        throw new RuntimeException('Passwords did not match.');
    }

    $count = (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    if ($count !== 0) {
        throw new RuntimeException('An admin already exists. This assignment supports one admin.');
    }
    $statement = db()->prepare('INSERT INTO admins (username, password_hash) VALUES (:username, :password_hash)');
    $statement->execute(['username' => $username, 'password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
    fwrite(STDOUT, "Admin account created.\n");
} catch (Throwable $error) {
    // Avoid printing database credentials, SQL, or a password in errors.
    fwrite(STDERR, 'Could not create admin: ' . ($error instanceof RuntimeException ? $error->getMessage() : 'Check configuration/database logs.') . PHP_EOL);
    exit(1);
}
