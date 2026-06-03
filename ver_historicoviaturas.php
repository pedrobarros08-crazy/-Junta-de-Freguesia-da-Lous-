<?php
require_once __DIR__ . '/security.php';
require_login();
include 'config.php';

$viaturaId = get_positive_int($_GET['viatura_id'] ?? 0);
if ($viaturaId <= 0) {
    die("Erro: Viatura inválida.");
}

fetch_one_assoc_or_fail(
    $conn,
    "SELECT id, nome FROM viaturas WHERE id = ?",
    [$viaturaId],
    'Erro: Viatura inválida.'
);

$stmt = prepare_and_execute_or_fail(
    $conn,
    "SELECT id, data_servico, km, intervencao, valor, fornecedor FROM manutencoes_viaturas WHERE id_viatura = ? ORDER BY data_servico DESC, id DESC",
    [$viaturaId],
    'ver_historicoviaturas.php',
    'Erro interno ao carregar histórico.'
);

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
    $data = format_sqlsrv_date($row['data_servico']);
    echo "<tr style='border-bottom: 1px solid #ddd;'>
            <td style='padding: 10px;'>" . htmlspecialchars($data, ENT_QUOTES, 'UTF-8') . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars((string) $row['km'], ENT_QUOTES, 'UTF-8') . " km</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['intervencao'], ENT_QUOTES, 'UTF-8') . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars(number_format((float) $row['valor'], 2, ',', '.'), ENT_QUOTES, 'UTF-8') . " €</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['fornecedor'], ENT_QUOTES, 'UTF-8') . "</td>
          </tr>";
}

if (!$temRegistos) {
    echo "<tr><td colspan='5' style='padding: 10px; text-align: center;'>Sem registos de manutenção.</td></tr>";
}

echo "</table>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
