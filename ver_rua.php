<?php
require_once __DIR__ . '/security.php';
require_login();
require_once __DIR__ . '/config.php';

$localidadeId = get_positive_int($_GET['localidade_id'] ?? 0);
$nomeRua      = sanitize_text_input($_GET['rua'] ?? '', 255);

fetch_one_assoc_or_fail(
    $conn,
    "SELECT id FROM localidades WHERE id = ?",
    [$localidadeId],
    'Erro: Localidade inválida.'
);
if ($nomeRua === '') {
    die("Erro: Nome de rua em falta.");
}

$stmt = prepare_and_execute_or_fail(
    $conn,
    "SELECT id, data_trabalho, tipo_trabalho, observacoes FROM trabalhos WHERE id_localidade = ? AND nome_rua = ? ORDER BY data_trabalho DESC, id DESC",
    [$localidadeId, $nomeRua],
    'ver_rua.php',
    'Erro interno ao carregar dados.'
);

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    $data = format_sqlsrv_date($row['data_trabalho']);
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
sqlsrv_close($conn);
?>
