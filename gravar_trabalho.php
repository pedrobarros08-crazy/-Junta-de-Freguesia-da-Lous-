<?php
require_once __DIR__ . '/security.php';
require_login();
include 'config.php';

function redirect_with_message($localidadeId, $status, $message)
{
    $status = in_array($status, ['success', 'error'], true) ? $status : 'error';
    $query = 'localidade_id=' . urlencode((string)(int)$localidadeId) . '&status=' . urlencode($status) . '&message=' . urlencode($message);
    header('Location: trabalhos.php?' . $query);
    exit;
}

function cleanup_sqlsrv($conn, ...$statements)
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: trabalhos.php');
    exit;
}

$stmtCheck = null;
$stmt = null;

$localidadeId = isset($_POST['localidade_id']) ? (int)$_POST['localidade_id'] : 0;

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    log_sensitive_action('trabalho_create_rejected_csrf', ['localidade_id' => $localidadeId]);
    cleanup_sqlsrv($conn);
    redirect_with_message($localidadeId, 'error', 'Pedido inválido.');
}

// Validar localidade na base de dados
$sqlCheck = "SELECT id FROM localidades WHERE id = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$localidadeId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_message(0, 'error', 'Localidade inválida.');
}
$nomeRua = sanitize_text_input($_POST['nome_rua'] ?? '', 255);
$dataInput = isset($_POST['data_trabalho']) ? trim($_POST['data_trabalho']) : '';
$tipoTrabalho = sanitize_text_input($_POST['tipo_trabalho'] ?? '', 255);
$observacoes = sanitize_text_input($_POST['observacoes'] ?? '', 2000);

if ($nomeRua === '' || $tipoTrabalho === '') {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_message($localidadeId, 'error', 'Rua e tipo de trabalho são obrigatórios.');
}

if (!is_valid_date_not_future($dataInput)) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_message($localidadeId, 'error', 'Data inválida.');
}

$sql = "INSERT INTO trabalhos (id_localidade, nome_rua, data_trabalho, tipo_trabalho, observacoes) VALUES (?, ?, ?, ?, ?)";
$params = [$localidadeId, $nomeRua, $dataInput, $tipoTrabalho, $observacoes];
$stmt = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_message($localidadeId, 'error', 'Erro interno ao preparar o registo.');
}

if (sqlsrv_execute($stmt)) {
    log_sensitive_action('trabalho_created', ['localidade_id' => $localidadeId, 'nome_rua' => $nomeRua]);
    cleanup_sqlsrv($conn, $stmt, $stmtCheck);
    redirect_with_message($localidadeId, 'success', 'Trabalho registado com sucesso.');
}

log_sensitive_action('trabalho_create_failed', ['localidade_id' => $localidadeId]);
cleanup_sqlsrv($conn, $stmt, $stmtCheck);
redirect_with_message($localidadeId, 'error', 'Erro ao guardar o trabalho.');
