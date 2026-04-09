<?php
include 'config.php';

// ✅ Validação do ID
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erro: ID da viatura inválido.");
}

$id_escolhido = intval($_GET['id']);

// ✅ Prepared Statement com SQLSRV
$sql    = "SELECT * FROM manutencoes WHERE id_viatura = ? ORDER BY data_servico DESC";
$params = array($id_escolhido);
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

echo "<table style='border-collapse: collapse; width: 100%;'>
        <tr style='background-color: #333; color: white;'>
            <th style='padding: 10px; border: 1px solid #ddd;'>Data</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Trabalho</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Fornecedor</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Kms</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Custo</th>
        </tr>";

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    $data = $row['data_servico'] instanceof DateTime
        ? $row['data_servico']->format('d/m/Y')
        : date('d/m/Y', strtotime($row['data_servico']));
    // ✅ Output escaping (proteção contra XSS)
    echo "<tr style='border-bottom: 1px solid #ddd;'>
            <td style='padding: 10px;'>" . htmlspecialchars($data) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['descricao']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['fornecedor']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['kms']) . " km</td>
            <td style='padding: 10px;'>" . htmlspecialchars(number_format($row['custo'], 2, ',', '.')) . " €</td>
          </tr>";
}

if (!$temRegistos) {
    echo "<tr><td colspan='5' style='padding: 10px; text-align: center;'>Sem registos de manutenção.</td></tr>";
}

echo "</table>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
