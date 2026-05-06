<?php
include 'config.php';

$viaturaId = isset($_GET['viatura_id']) ? (int)$_GET['viatura_id'] : 0;

// Validar viatura na base de dados
$sqlCheck = "SELECT id, nome FROM viaturas WHERE id = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$viaturaId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck)) {
    die("Erro: Viatura inválida.");
}
$viaturaRow = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
if (!$viaturaRow) {
    die("Erro: Viatura inválida.");
}

$sql  = "SELECT id, data_servico, km, intervencao, valor, fornecedor FROM manutencoes_viaturas WHERE id_viatura = ? ORDER BY data_servico DESC, id DESC";
$params = [$viaturaId];
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
            <th style='padding: 10px; border: 1px solid #ddd;'>Data</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>KM</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Intervenção</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Valor</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Fornecedor</th>
        </tr>";

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    $data = $row['data_servico'] instanceof DateTime
        ? $row['data_servico']->format('d/m/Y')
        : date('d/m/Y', strtotime($row['data_servico']));
    echo "<tr style='border-bottom: 1px solid #ddd;'>
            <td style='padding: 10px;'>" . htmlspecialchars($data) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars((string) $row['km']) . " km</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['intervencao']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars(number_format((float) $row['valor'], 2, ',', '.')) . " €</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['fornecedor']) . "</td>
          </tr>";
}

if (!$temRegistos) {
    echo "<tr><td colspan='5' style='padding: 10px; text-align: center;'>Sem registos de manutenção.</td></tr>";
}

echo "</table>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
