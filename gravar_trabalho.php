<?php
include 'config.php';

function redirect_with_message($localidadeId, $status, $message)
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

$localidadeId = isset($_POST['localidade_id']) ? (int)$_POST['localidade_id'] : 0;

// Validar localidade na base de dados
$sqlCheck = "SELECT id FROM localidades WHERE id = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$localidadeId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    redirect_with_message(0, 'error', 'Localidade inválida.');
}
$nomeRua = isset($_POST['nome_rua']) ? trim($_POST['nome_rua']) : '';
$dataInput = isset($_POST['data_trabalho']) ? trim($_POST['data_trabalho']) : '';
$tipoTrabalho = isset($_POST['tipo_trabalho']) ? trim($_POST['tipo_trabalho']) : '';
$observacoes = isset($_POST['observacoes']) ? trim($_POST['observacoes']) : '';

if ($nomeRua === '' || $tipoTrabalho === '') {
    redirect_with_message($localidadeId, 'error', 'Rua e tipo de trabalho são obrigatórios.');
}

$dataObj = DateTime::createFromFormat('Y-m-d', $dataInput);
if (!$dataObj || $dataObj->format('Y-m-d') !== $dataInput) {
    redirect_with_message($localidadeId, 'error', 'Data inválida.');
}

if (mb_strlen($nomeRua, 'UTF-8') > 255 || mb_strlen($tipoTrabalho, 'UTF-8') > 255 || mb_strlen($observacoes, 'UTF-8') > 2000) {
    redirect_with_message($localidadeId, 'error', 'Um ou mais campos excedem o tamanho permitido.');
}

$sql = "INSERT INTO trabalhos (id_localidade, nome_rua, data_trabalho, tipo_trabalho, observacoes) VALUES (?, ?, ?, ?, ?)";
$params = [$localidadeId, $nomeRua, $dataInput, $tipoTrabalho, $observacoes];
$stmt = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    redirect_with_message($localidadeId, 'error', 'Erro interno ao preparar o registo.');
}

if (sqlsrv_execute($stmt)) {
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    redirect_with_message($localidadeId, 'success', 'Trabalho registado com sucesso.');
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
redirect_with_message($localidadeId, 'error', 'Erro ao guardar o trabalho.');
