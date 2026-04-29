<?php
include 'config.php';

function redirect_with_message_viatura($viaturaId, $status, $message)
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

$viaturaId = isset($_POST['viatura_id']) ? (int)$_POST['viatura_id'] : 0;

// Validar viatura na base de dados
$sqlCheck = "SELECT id FROM viaturas WHERE id = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$viaturaId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    redirect_with_message_viatura(0, 'error', 'Viatura inválida.');
}

$dataServico = isset($_POST['data_servico']) ? trim($_POST['data_servico']) : '';
$km          = isset($_POST['km'])           ? trim($_POST['km'])           : '';
$intervencao = isset($_POST['intervencao'])  ? trim($_POST['intervencao'])  : '';
$valor       = isset($_POST['valor'])        ? trim($_POST['valor'])        : '';
$fornecedor  = isset($_POST['fornecedor'])   ? trim($_POST['fornecedor'])   : '';

$dataObj = DateTime::createFromFormat('Y-m-d', $dataServico);
if (!$dataObj || $dataObj->format('Y-m-d') !== $dataServico) {
    redirect_with_message_viatura($viaturaId, 'error', 'Data de serviço inválida.');
}

if ($intervencao === '' || $fornecedor === '') {
    redirect_with_message_viatura($viaturaId, 'error', 'Intervenção e fornecedor são obrigatórios.');
}

if (!is_numeric($km) || (int)$km < 0) {
    redirect_with_message_viatura($viaturaId, 'error', 'KM inválido.');
}

if (!is_numeric($valor) || (float)$valor < 0) {
    redirect_with_message_viatura($viaturaId, 'error', 'Valor inválido.');
}

if (mb_strlen($intervencao, 'UTF-8') > 500 || mb_strlen($fornecedor, 'UTF-8') > 255) {
    redirect_with_message_viatura($viaturaId, 'error', 'Um ou mais campos excedem o tamanho permitido.');
}

$sql = "INSERT INTO manutencoes_viaturas (id_viatura, data_servico, km, intervencao, valor, fornecedor) VALUES (?, ?, ?, ?, ?, ?)";
$params = [$viaturaId, $dataServico, (int)$km, $intervencao, (float)$valor, $fornecedor];
$stmt = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    redirect_with_message_viatura($viaturaId, 'error', 'Erro interno ao preparar o registo.');
}

if (sqlsrv_execute($stmt)) {
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    redirect_with_message_viatura($viaturaId, 'success', 'Registo guardado com sucesso.');
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
redirect_with_message_viatura($viaturaId, 'error', 'Erro ao guardar o registo.');
