<?php
declare(strict_types=1);

// Small zero-dependency unit tests; no MySQL server is needed for these.
require __DIR__ . '/../lib/validation.php';
require __DIR__ . '/../lib/helpers.php';

$failures = 0;
$total = 0;
function check(string $label, bool $condition): void
{
    global $failures, $total;
    $total++;
    echo ($condition ? '[PASS] ' : '[FAIL] ') . $label . PHP_EOL;
    if (!$condition) {
        $failures++;
    }
}

$good = validate_contact(['name' => 'Sara Ahmed', 'email' => 'sara@example.com', 'phone' => '+966 55 123 4567']);
check('Valid input accepted', $good['errors'] === []);
check('Input trimmed', $good['data']['name'] === 'Sara Ahmed');
$invalid = validate_contact(['name' => 'A', 'email' => 'not-an-email', 'phone' => 'abc']);
check('All three invalid fields rejected', count($invalid['errors']) === 3);
$attack = e('<script>window.alert(1)</script>');
check('HTML script escaped', !str_contains($attack, '<script>') && str_contains($attack, '&lt;script&gt;'));
check('Attribute quotes escaped', e('" onmouseover="alert(1)') === '&quot; onmouseover=&quot;alert(1)');
$unicode = validate_contact(['name' => 'محمد أحمد', 'email' => 'm@example.com', 'phone' => '+966 55 123 4567']);
check('Unicode names accepted', $unicode['errors'] === []);
check('Control characters rejected in names', isset(validate_contact(['name' => "Sara\nAhmed", 'email' => 's@example.com', 'phone' => '0551234567'])['errors']['name']));
check('Email header injections rejected', isset(validate_contact(['name' => 'Valid Name', 'email' => "foo@example.com\r\nBcc:bad@example.com", 'phone' => '0551234567'])['errors']['email']));
check('Malformed phone numbers rejected', isset(validate_contact(['name' => 'Valid Name', 'email' => 'v@example.com', 'phone' => 'DROP TABLE contacts'])['errors']['phone']));
check('Non-string input rejected', count(validate_contact(['name' => [], 'email' => [], 'phone' => []])['errors']) === 3);
check('Name over 100 chars rejected', isset(validate_contact(['name' => str_repeat('a', 101), 'email' => 'v@example.com', 'phone' => '0551234567'])['errors']['name']));
check('CSRF token random hex, 64 chars', preg_match('/^[0-9a-f]{64}$/', csrf_token()) === 1);
check('CSRF token stable within session', hash_equals(csrf_token(), csrf_token()));

echo PHP_EOL . ($total - $failures) . '/' . $total . ' tests passed.' . PHP_EOL;
exit($failures === 0 ? 0 : 1);
