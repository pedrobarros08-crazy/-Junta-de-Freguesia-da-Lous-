<?php
require_once __DIR__ . '/security.php';
require_login();
include 'config.php';

require_post_request_or_redirect('viaturas.php');

$stmtCheck = null;
$stmt = null;

$viaturaId = isset($_POST['viatura_id']) ? (int)$_POST['viatura_id'] : 0;

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    log_sensitive_action('viatura_create_rejected_csrf', ['viatura_id' => $viaturaId]);
    cleanup_sqlsrv($conn);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Pedido inválido.');
}

// Validar viatura na base de dados
$sqlCheck = "SELECT id FROM viaturas WHERE id = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$viaturaId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => 0], 'error', 'Viatura inválida.');
}

$dataServico = isset($_POST['data_servico']) ? trim($_POST['data_servico']) : '';
$km          = isset($_POST['km'])           ? trim($_POST['km'])           : '';
$intervencao = sanitize_text_input($_POST['intervencao'] ?? '', 500);
$valor       = isset($_POST['valor'])        ? trim($_POST['valor'])        : '';
$fornecedor  = sanitize_text_input($_POST['fornecedor'] ?? '', 255);

if (!is_valid_date_not_future($dataServico)) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Data de serviço inválida.');
}

if ($intervencao === '' || $fornecedor === '') {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Intervenção e fornecedor são obrigatórios.');
}

if (!preg_match('/^\d+$/', $km)) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'KM inválido.');
}

if (!preg_match('/^\d+(?:[.,]\d{1,2})?$/', $valor)) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Valor inválido.');
}
$valorNormalizado = (float) str_replace(',', '.', $valor);
if ($valorNormalizado <= 0) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Valor inválido.');
}

$sql = "INSERT INTO manutencoes_viaturas (id_viatura, data_servico, km, intervencao, valor, fornecedor) VALUES (?, ?, ?, ?, ?, ?)";
$params = [$viaturaId, $dataServico, (int)$km, $intervencao, $valorNormalizado, $fornecedor];
$stmt = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    cleanup_sqlsrv($conn, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Erro interno ao preparar o registo.');
}

if (sqlsrv_execute($stmt)) {
    log_sensitive_action('viatura_registo_created', ['viatura_id' => $viaturaId, 'fornecedor' => $fornecedor]);
    cleanup_sqlsrv($conn, $stmt, $stmtCheck);
    redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'success', 'Registo guardado com sucesso.');
}

log_sensitive_action('viatura_registo_create_failed', ['viatura_id' => $viaturaId]);
cleanup_sqlsrv($conn, $stmt, $stmtCheck);
redirect_with_status('viaturas.php', ['viatura_id' => (int) $viaturaId], 'error', 'Erro ao guardar o registo.');
