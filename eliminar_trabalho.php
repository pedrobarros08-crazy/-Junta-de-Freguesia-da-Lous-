<?php
include 'config.php';

function redirect_with_message($trabalhoId, $localidadeId, $status, $message)
{
    $status = in_array($status, ['success', 'error'], true) ? $status : 'error';
    $query = 'localidade_id=' . urlencode((string)(int)$localidadeId) . '&status=' . urlencode($status) . '&message=' . urlencode($message);
    header('Location: trabalhos.php?' . $query);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: trabalhos.php');
    exit;
}

$trabalhoId = isset($_POST['trabalho_id']) ? (int)$_POST['trabalho_id'] : 0;
$localidadeId = isset($_POST['localidade_id']) ? (int)$_POST['localidade_id'] : 0;

if ($trabalhoId <= 0 || $localidadeId <= 0) {
    redirect_with_message($trabalhoId, $localidadeId, 'error', 'Dados inválidos.');
}

// Validar que o trabalho pertence à localidade
$sqlCheck = "SELECT id FROM trabalhos WHERE id = ? AND id_localidade = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$trabalhoId, $localidadeId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    redirect_with_message($trabalhoId, $localidadeId, 'error', 'Trabalho não encontrado ou inválido.');
}

// Eliminar o trabalho
$sql = "DELETE FROM trabalhos WHERE id = ? AND id_localidade = ?";
$stmt = sqlsrv_prepare($conn, $sql, [$trabalhoId, $localidadeId]);

if ($stmt === false) {
    redirect_with_message($trabalhoId, $localidadeId, 'error', 'Erro ao preparar a eliminação.');
}

if (sqlsrv_execute($stmt)) {
    sqlsrv_free_stmt($stmt);
    sqlsrv_free_stmt($stmtCheck);
    sqlsrv_close($conn);
    redirect_with_message($trabalhoId, $localidadeId, 'success', 'Trabalho eliminado com sucesso.');
}

sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($stmtCheck);
sqlsrv_close($conn);
redirect_with_message($trabalhoId, $localidadeId, 'error', 'Erro ao eliminar o trabalho.');
