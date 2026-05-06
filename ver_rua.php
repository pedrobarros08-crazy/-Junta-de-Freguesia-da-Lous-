<?php
include 'config.php';

$localidadeId = isset($_GET['localidade_id']) ? (int)$_GET['localidade_id'] : 0;
$nomeRua      = isset($_GET['rua'])           ? trim($_GET['rua'])           : '';

// Validar localidade na base de dados
$sqlCheck = "SELECT id FROM localidades WHERE id = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$localidadeId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck) || !sqlsrv_fetch_array($stmtCheck)) {
    die("Erro: Localidade inválida.");
}
if ($nomeRua === '') {
    die("Erro: Nome de rua em falta.");
}

$sql    = "SELECT id, data_trabalho, tipo_trabalho, observacoes FROM trabalhos WHERE id_localidade = ? AND nome_rua = ? ORDER BY data_trabalho DESC, id DESC";
$params = [$localidadeId, $nomeRua];
$stmt   = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Erro na preparação da query: " . htmlspecialchars($msg));
}

if (!sqlsrv_execute($stmt)) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Erro ao executar a query: " . htmlspecialchars($msg));
}

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    $data = $row['data_trabalho'] instanceof DateTime
        ? $row['data_trabalho']->format('d/m/Y')
        : date('d/m/Y', strtotime($row['data_trabalho']));
    echo htmlspecialchars($data) . ' — ' . htmlspecialchars($row['tipo_trabalho']);
    if (!empty($row['observacoes'])) {
        echo ': ' . htmlspecialchars((string) $row['observacoes']);
    }
    echo "<br>";
}

if (!$temRegistos) {
    echo "Sem registos para esta rua.";
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
