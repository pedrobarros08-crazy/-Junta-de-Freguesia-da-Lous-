<?php
require_once __DIR__ . '/security.php';
require_login();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: viaturas.php');
    exit;
}

$manutencaoId = isset($_POST['manutencao_id']) ? (int)$_POST['manutencao_id'] : 0;
$viaturaId = isset($_POST['viatura_id']) ? (int)$_POST['viatura_id'] : 0;
$stmtCheck = null;
$stmt = null;

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    log_sensitive_action('viatura_delete_rejected_csrf', ['manutencao_id' => $manutencaoId, 'viatura_id' => $viaturaId]);
    cleanup_sqlsrv($conn);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Pedido inválido.');
}

if ($manutencaoId <= 0 || $viaturaId <= 0) {
    cleanup_sqlsrv($conn);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Dados inválidos.');
}

// Validar que a manutenção pertence à viatura
$sqlCheck = "SELECT id FROM manutencoes_viaturas WHERE id = ? AND id_viatura = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$manutencaoId, $viaturaId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Manutenção não encontrada ou inválida.');
}

// Eliminar a manutenção
$sql = "DELETE FROM manutencoes_viaturas WHERE id = ? AND id_viatura = ?";
$stmt = sqlsrv_prepare($conn, $sql, [$manutencaoId, $viaturaId]);

if ($stmt === false) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Erro ao preparar a eliminação.');
}

if (sqlsrv_execute($stmt)) {
    log_sensitive_action('viatura_registo_deleted', ['manutencao_id' => $manutencaoId, 'viatura_id' => $viaturaId]);
    cleanup_sqlsrv($conn, $stmt, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'success', 'Registo de manutenção eliminado com sucesso.');
}

log_sensitive_action('viatura_registo_delete_failed', ['manutencao_id' => $manutencaoId, 'viatura_id' => $viaturaId]);
cleanup_sqlsrv($conn, $stmt, $stmtCheck);
redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Erro ao eliminar o registo de manutenção.');
