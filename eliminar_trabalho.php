<?php
require_once __DIR__ . '/security.php';
require_login();
include 'config.php';

require_post_request_or_redirect('trabalhos.php');

$trabalhoId = isset($_POST['trabalho_id']) ? (int)$_POST['trabalho_id'] : 0;
$localidadeId = isset($_POST['localidade_id']) ? (int)$_POST['localidade_id'] : 0;
$stmtCheck = null;
$stmt = null;

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    log_sensitive_action('trabalho_delete_rejected_csrf', ['trabalho_id' => $trabalhoId, 'localidade_id' => $localidadeId]);
    cleanup_sqlsrv($conn);
    redirect_with_status('trabalhos.php', ['localidade_id' => (int) $localidadeId], 'error', 'Pedido inválido.');
}

if ($trabalhoId <= 0 || $localidadeId <= 0) {
    cleanup_sqlsrv($conn);
    redirect_with_status('trabalhos.php', ['localidade_id' => (int) $localidadeId], 'error', 'Dados inválidos.');
}

// Validar que o trabalho pertence à localidade
$sqlCheck = "SELECT id FROM trabalhos WHERE id = ? AND id_localidade = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$trabalhoId, $localidadeId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('trabalhos.php', ['localidade_id' => (int) $localidadeId], 'error', 'Trabalho não encontrado ou inválido.');
}

// Eliminar o trabalho
$sql = "DELETE FROM trabalhos WHERE id = ? AND id_localidade = ?";
$stmt = sqlsrv_prepare($conn, $sql, [$trabalhoId, $localidadeId]);

if ($stmt === false) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('trabalhos.php', ['localidade_id' => (int) $localidadeId], 'error', 'Erro ao preparar a eliminação.');
}

if (sqlsrv_execute($stmt)) {
    log_sensitive_action('trabalho_deleted', ['trabalho_id' => $trabalhoId, 'localidade_id' => $localidadeId]);
    cleanup_sqlsrv($conn, $stmt, $stmtCheck);
    redirect_with_status('trabalhos.php', ['localidade_id' => (int) $localidadeId], 'success', 'Trabalho eliminado com sucesso.');
}

log_sensitive_action('trabalho_delete_failed', ['trabalho_id' => $trabalhoId, 'localidade_id' => $localidadeId]);
cleanup_sqlsrv($conn, $stmt, $stmtCheck);
redirect_with_status('trabalhos.php', ['localidade_id' => (int) $localidadeId], 'error', 'Erro ao eliminar o trabalho.');
