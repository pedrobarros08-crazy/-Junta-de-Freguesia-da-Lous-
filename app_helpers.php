<?php

function cleanup_sqlsrv($conn, ...$statements): void
{
    foreach ($statements as $statement) {
        if ($statement) {
            sqlsrv_free_stmt($statement);
        }
    }

    if ($conn) {
        sqlsrv_close($conn);
    }
}

function redirect_with_status(string $path, array $queryParams, string $status, string $message): void
{
    $status = in_array($status, ['success', 'error'], true) ? $status : 'error';
    $queryParams['status'] = $status;
    $queryParams['message'] = $message;

    $query = http_build_query($queryParams);
    header('Location: ' . $path . ($query !== '' ? '?' . $query : ''));
    exit;
}

function format_sqlsrv_date($value): string
{
    if ($value instanceof DateTimeInterface) {
        return $value->format('d/m/Y');
    }

    $rawDate = (string) $value;
    $ts = strtotime($rawDate);
    return $ts !== false ? date('d/m/Y', $ts) : $rawDate;
}

function require_post_request_or_redirect(string $path): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        header('Location: ' . $path);
        exit;
    }
}

function get_status_and_message_from_query(): array
{
    $status = isset($_GET['status']) ? (string) $_GET['status'] : '';
    $status = in_array($status, ['success', 'error'], true) ? $status : '';
    $message = isset($_GET['message']) ? (string) $_GET['message'] : '';

    return [$status, $message];
}

function render_user_session_actions(): void
{
    ?>
    <span>Utilizador: <?php echo htmlspecialchars(get_authenticated_username(), ENT_QUOTES, 'UTF-8'); ?></span>
    <form method="POST" action="signin.php" style="margin:0;">
        <input type="hidden" name="action" value="logout">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="btn-logout">Terminar sessão</button>
    </form>
    <?php
}

function get_positive_int($value): int
{
    $parsed = (int) $value;
    return $parsed > 0 ? $parsed : 0;
}

function fetch_one_assoc_or_fail($conn, string $sql, array $params, string $publicErrorMessage)
{
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if ($stmt === false || !sqlsrv_execute($stmt)) {
        cleanup_sqlsrv($conn, $stmt);
        die($publicErrorMessage);
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    if (!$row) {
        cleanup_sqlsrv($conn);
        die($publicErrorMessage);
    }

    return $row;
}

function prepare_and_execute_or_fail($conn, string $sql, array $params, string $logPrefix, string $publicErrorMessage)
{
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if ($stmt === false) {
        error_log($logPrefix . ' prepare falhou: ' . print_r(sqlsrv_errors(), true));
        cleanup_sqlsrv($conn);
        die($publicErrorMessage);
    }

    if (!sqlsrv_execute($stmt)) {
        error_log($logPrefix . ' execute falhou: ' . print_r(sqlsrv_errors(), true));
        cleanup_sqlsrv($conn, $stmt);
        die($publicErrorMessage);
    }

    return $stmt;
}
