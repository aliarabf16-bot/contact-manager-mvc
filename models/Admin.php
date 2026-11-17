<?php
declare(strict_types=1);

final class Admin
{
    public function verify(string $username, string $password): ?array
    {
        $statement = db()->prepare('SELECT id, username, password_hash FROM admins WHERE username = :username LIMIT 1');
        $statement->execute(['username' => $username]);
        $admin = $statement->fetch();
        if ($admin === false || !password_verify($password, $admin['password_hash'])) {
            return null;
        }
        return ['id' => (int) $admin['id'], 'username' => $admin['username']];
    }
}
