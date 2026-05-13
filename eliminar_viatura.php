<?php
include 'config.php';

function redirect_with_message($manutencaoId, $viaturaId, $status, $message)
{
    $status = in_array($status, ['success', 'error'], true) ? $status : 'error';
    $query = 'viatura_id=' . urlencode((string)(int)$viaturaId) . '&status=' . urlencode($status) . '&message=' . urlencode($message);
    header('Location: viaturas.php?' . $query);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: viaturas.php');
    exit;
}

$manutencaoId = isset($_POST['manutencao_id']) ? (int)$_POST['manutencao_id'] : 0;
$viaturaId = isset($_POST['viatura_id']) ? (int)$_POST['viatura_id'] : 0;

if ($manutencaoId <= 0 || $viaturaId <= 0) {
    redirect_with_message($manutencaoId, $viaturaId, 'error', 'Dados inválidos.');
}

// Validar que a manutenção pertence à viatura
$sqlCheck = "SELECT id FROM manutencoes_viaturas WHERE id = ? AND id_viatura = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$manutencaoId, $viaturaId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    redirect_with_message($manutencaoId, $viaturaId, 'error', 'Manutenção não encontrada ou inválida.');
}

// Eliminar a manutenção
$sql = "DELETE FROM manutencoes_viaturas WHERE id = ? AND id_viatura = ?";
$stmt = sqlsrv_prepare($conn, $sql, [$manutencaoId, $viaturaId]);

if ($stmt === false) {
    redirect_with_message($manutencaoId, $viaturaId, 'error', 'Erro ao preparar a eliminação.');
}

if (sqlsrv_execute($stmt)) {
    sqlsrv_free_stmt($stmt);
    sqlsrv_free_stmt($stmtCheck);
    sqlsrv_close($conn);
    redirect_with_message($manutencaoId, $viaturaId, 'success', 'Registo de manutenção eliminado com sucesso.');
}

sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($stmtCheck);
sqlsrv_close($conn);
redirect_with_message($manutencaoId, $viaturaId, 'error', 'Erro ao eliminar o registo de manutenção.');
