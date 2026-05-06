<?php
include 'config.php';

$localidadeId = isset($_GET['localidade_id']) ? (int)$_GET['localidade_id'] : 0;

// Validar localidade na base de dados
$sqlCheck = "SELECT id, nome FROM localidades WHERE id = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$localidadeId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck)) {
    die("Erro: Localidade inválida.");
}
$localidadeRow = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
if (!$localidadeRow) {
    die("Erro: Localidade inválida.");
}

$sql  = "SELECT id, nome_rua, data_trabalho, tipo_trabalho, observacoes FROM trabalhos WHERE id_localidade = ? ORDER BY data_trabalho DESC, id DESC";
$params = [$localidadeId];
$stmt = sqlsrv_prepare($conn, $sql, $params);

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

echo "<table style='border-collapse: collapse; width: 100%;'>
        <tr style='background-color: #333; color: white;'>
            <th style='padding: 10px; border: 1px solid #ddd;'>Rua</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Data</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Tipo de Trabalho</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Observações</th>
        </tr>";

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    $data = $row['data_trabalho'] instanceof DateTime
        ? $row['data_trabalho']->format('d/m/Y')
        : date('d/m/Y', strtotime($row['data_trabalho']));
    echo "<tr style='border-bottom: 1px solid #ddd;'>
            <td style='padding: 10px;'>" . htmlspecialchars($row['nome_rua']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($data) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['tipo_trabalho']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars((string) $row['observacoes']) . "</td>
          </tr>";
}

if (!$temRegistos) {
    echo "<tr><td colspan='4' style='padding: 10px; text-align: center;'>Sem registos de trabalho.</td></tr>";
}

echo "</table>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
