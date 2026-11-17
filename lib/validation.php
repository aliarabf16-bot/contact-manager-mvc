<?php
declare(strict_types=1);

/** @return array{data: array{name:string,email:string,phone:string}, errors: array<string,string>} */
function validate_contact(array $input): array
{
    $name = is_string($input['name'] ?? null) ? trim($input['name']) : '';
    $email = is_string($input['email'] ?? null) ? trim($input['email']) : '';
    $phone = is_string($input['phone'] ?? null) ? trim($input['phone']) : '';
    $errors = [];

    // Unicode-safe length limit; prevents control characters and empty names.
    if (preg_match('/^[^\x00-\x1F\x7F]{2,100}$/u', $name) !== 1) {
        $errors['name'] = 'Name must contain 2–100 characters and no control characters.';
    }
    if (strlen($email) > 190 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Please enter a valid email address (max 190 characters).';
    }
    if (strlen($phone) > 30 || preg_match('/^\+?[0-9][0-9 ()-]{4,29}$/D', $phone) !== 1) {
        $errors['phone'] = 'Enter a phone number with 5–30 characters using digits, spaces, +, - or parentheses.';
    }

    return ['data' => compact('name', 'email', 'phone'), 'errors' => $errors];
}
