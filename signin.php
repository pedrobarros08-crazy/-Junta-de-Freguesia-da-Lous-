<?php
require_once __DIR__ . '/security.php';

$next = safe_next_path($_GET['next'] ?? 'viaturas.php');
$status = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';
    if ($action === 'logout') {
        if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
            header('Location: signin.php?status=error&message=' . urlencode('Pedido inválido. Tente novamente.'));
            exit;
        }
        if (is_authenticated()) {
            log_sensitive_action('logout', ['username' => get_authenticated_username()]);
        }
        logout_user();
        header('Location: signin.php?status=success&message=' . urlencode('Sessão terminada com sucesso.'));
        exit;
    }

    if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
        $status = 'error';
        $message = 'Pedido inválido. Tente novamente.';
    } else {
        $username = sanitize_text_input($_POST['username'] ?? '', 100);
        $password = (string) ($_POST['password'] ?? '');
        if ($username === '' || $password === '') {
            $status = 'error';
            $message = 'Credenciais inválidas.';
        } elseif (authenticate_credentials($username, $password)) {
            login_user($username);
            log_sensitive_action('login_success', ['username' => $username]);
            header('Location: ' . safe_next_path($_POST['next'] ?? $next));
            exit;
        } else {
            log_sensitive_action('login_failure', ['username' => $username]);
            $status = 'error';
            $message = 'Credenciais inválidas.';
        }
    }
}

if ($status === '' && isset($_GET['status'])) {
    $queryStatus = (string) $_GET['status'];
    if (in_array($queryStatus, ['success', 'error'], true)) {
        $status = $queryStatus;
        $message = sanitize_text_input($_GET['message'] ?? '', 255);
    }
}

if (is_authenticated()) {
    header('Location: ' . $next);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sessão</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; margin: 0; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 16px; box-sizing: border-box; }
        .container { width: 100%; max-width: 420px; background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); }
        label { display: block; margin-top: 12px; font-weight: 600; }
        input { width: 100%; box-sizing: border-box; padding: 10px; margin-top: 6px; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: 18px; width: 100%; padding: 12px; border: none; border-radius: 6px; cursor: pointer; background: #2ecc71; color: #fff; font-weight: 700; }
        button:hover { background: #27ae60; }
        .status-error { margin-top: 8px; padding: 10px; border-radius: 6px; color: #842029; background: #f8d7da; }
        .status-success { margin-top: 8px; padding: 10px; border-radius: 6px; color: #0f5132; background: #d1e7dd; }
    </style>
</head>
<body>
<div class="container">
    <h2>Autenticação</h2>
    <?php if ($status === 'error'): ?><div class="status-error"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
    <?php if ($status === 'success'): ?><div class="status-success"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="next" value="<?php echo htmlspecialchars($next, ENT_QUOTES, 'UTF-8'); ?>">
        <label for="username">Utilizador</label>
        <input id="username" name="username" type="text" maxlength="100" required>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>
        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>
