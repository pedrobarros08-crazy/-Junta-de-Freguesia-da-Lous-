<?php
require_once __DIR__ . '/security.php';
require_login();
include 'config.php';

$localidadeId = isset($_GET['localidade_id']) ? (int)$_GET['localidade_id'] : 0;
$nomeRua      = sanitize_text_input($_GET['rua'] ?? '', 255);

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
    error_log('ver_rua.php prepare falhou: ' . print_r(sqlsrv_errors(), true));
    die("Erro interno ao carregar dados.");
}

if (!sqlsrv_execute($stmt)) {
    error_log('ver_rua.php execute falhou: ' . print_r(sqlsrv_errors(), true));
    die("Erro interno ao carregar dados.");
}

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    if ($row['data_trabalho'] instanceof DateTime) {
        $data = $row['data_trabalho']->format('d/m/Y');
    } else {
        $rawDate = (string) $row['data_trabalho'];
        $ts = strtotime($rawDate);
        $data = $ts !== false ? date('d/m/Y', $ts) : $rawDate;
    }
    echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8') . ' — ' . htmlspecialchars($row['tipo_trabalho'], ENT_QUOTES, 'UTF-8');
    if (!empty($row['observacoes'])) {
        echo ': ' . htmlspecialchars((string) $row['observacoes'], ENT_QUOTES, 'UTF-8');
    }
    echo "<br>";
}

if (!$temRegistos) {
    echo "Sem registos para esta rua.";
}

sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($stmtCheck);
sqlsrv_close($conn);
?>
