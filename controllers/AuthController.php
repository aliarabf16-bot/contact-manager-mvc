<?php
declare(strict_types=1);

final class AuthController
{
    public function loginForm(): void
    {
        if (current_admin() !== null) {
            redirect('list');
        }
        render('auth/login', ['title' => 'Admin sign in']);
    }

    public function signIn(): void
    {
        require_csrf();
        if (current_admin() !== null) {
            redirect('list');
        }
        $username = is_string($_POST['username'] ?? null) ? trim($_POST['username']) : '';
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

        $attempts = $_SESSION['login_attempts'] ?? [];
        if (!is_array($attempts)) {
            $attempts = [];
        }
        $attempts = array_values(array_filter($attempts, static fn ($time): bool => is_int($time) && $time > time() - 900));
        if (count($attempts) >= 5) {
            $_SESSION['login_attempts'] = $attempts;
            flash('error', 'Too many attempts. Try again in 15 minutes.');
            redirect('login');
        }

        $admin = (strlen($username) <= 50 && strlen($password) <= 4096 && $username !== '' && $password !== '')
            ? (new Admin())->verify($username, $password)
            : null;

        if ($admin === null) {
            $attempts[] = time();
            $_SESSION['login_attempts'] = $attempts;
            flash('error', 'Invalid username or password.');
            redirect('login');
        }

        session_regenerate_id(true);
        $_SESSION['admin'] = $admin;
        unset($_SESSION['login_attempts']);
        flash('success', 'Welcome back, ' . $admin['username'] . '.');
        redirect('list');
    }

    public function logout(): void
    {
        require_login();
        require_csrf();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $parameters = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 3600,
                'path' => $parameters['path'],
                'domain' => $parameters['domain'],
                'secure' => $parameters['secure'],
                'httponly' => $parameters['httponly'],
                'samesite' => $parameters['samesite'],
            ]);
        }
        session_destroy();
        header('Location: index.php?action=login', true, 303);
        exit;
    }
}
