<?php
require_once __DIR__ . '/loader.env.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function app_env(): string
{
    $env = getenv('APP_ENV');
    return $env ? strtolower(trim($env)) : 'production';
}

function is_production(): bool
{
    return app_env() === 'production';
}

function get_csrf_token(): string
{
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validate_csrf_token(?string $token): bool
{
    if (!is_string($token) || $token === '' || empty($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function is_authenticated(): bool
{
    return !empty($_SESSION['user_id']) && !empty($_SESSION['username']);
}

function get_authenticated_user_id(): int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
}

function get_authenticated_username(): string
{
    return isset($_SESSION['username']) ? (string) $_SESSION['username'] : '';
}

function safe_next_path(?string $path): string
{
    $path = trim((string) $path);
    if ($path === '' || preg_match('#^(https?:)?//#i', $path) || strpos($path, '..') !== false) {
        return 'viaturas.php';
    }

    if (strpos($path, '/') === 0) {
        $path = ltrim($path, '/');
    }

    if (!preg_match('/^[a-zA-Z0-9._?=&\\/-]+$/', $path)) {
        return 'viaturas.php';
    }

    return $path;
}

function require_login(): void
{
    if (is_authenticated()) {
        return;
    }

    $next = safe_next_path($_SERVER['REQUEST_URI'] ?? 'viaturas.php');
    header('Location: signin.php?next=' . urlencode($next));
    exit;
}

function log_sensitive_action(string $action, array $context = []): void
{
    $payload = [
        'timestamp' => date('c'),
        'action' => $action,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_id' => get_authenticated_user_id(),
        'context' => $context,
    ];

    error_log('AUDIT ' . json_encode($payload, JSON_UNESCAPED_UNICODE));
}

function sanitize_text_input(?string $value, int $maxLength): string
{
    $value = trim((string) $value);
    $value = strip_tags($value);
    if (mb_strlen($value, 'UTF-8') > $maxLength) {
        $value = mb_substr($value, 0, $maxLength, 'UTF-8');
    }

    return $value;
}

function is_valid_date_not_future(string $date): bool
{
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
        return false;
    }

    $today = new DateTime('today');
    return $dateObj <= $today;
}

function authenticate_credentials(string $username, string $password): bool
{
    $expectedUser = trim((string) getenv('APP_USER'));
    $expectedHash = trim((string) getenv('APP_PASSWORD_HASH'));
    $expectedPassword = (string) getenv('APP_PASSWORD');

    if ($expectedUser === '') {
        return false;
    }

    if (!hash_equals($expectedUser, $username)) {
        return false;
    }

    if ($expectedHash !== '') {
        return password_verify($password, $expectedHash);
    }

    if ($expectedPassword !== '') {
        return hash_equals($expectedPassword, $password);
    }

    return false;
}

function login_user(string $username): void
{
    session_regenerate_id(true);
    $configuredUserId = filter_var(getenv('APP_USER_ID'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $_SESSION['user_id'] = $configuredUserId !== false ? (int) $configuredUserId : 1;
    $_SESSION['username'] = $username;
    get_csrf_token();
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
}
?>
